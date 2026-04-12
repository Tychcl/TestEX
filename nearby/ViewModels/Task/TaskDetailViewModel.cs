using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Windows.Input;
using nearby.Classes;
using nearby.Interfaces;
using nearby.Models;
using nearby.Services;
using nearby.Views.Main;

namespace nearby.ViewModels
{
    [QueryProperty(nameof(taskId), "id")]
    public class TaskDetailViewModel : BaseViewModel, INotifyPropertyChanged, IQueryAttributable
    {
        private readonly ITaskService _taskService;
        private readonly IUserService _userService;
        private readonly IServiceProvider _serviceProvider;

        private int taskId;
        private TaskItem _task;
        private bool _isOwner;
        private bool _hasVolunteered;
        private bool _canVolunteer;
        private string _volunteerStatus;
        private ObservableCollection<TaskVolunteerInfo> _volunteers = new();

        public ObservableCollection<TaskVolunteerInfo> Volunteers
        {
            get => _volunteers;
            set => SetField(ref _volunteers, value);
        }

        public TaskItem Task
        {
            get => _task;
            set
            {
                SetField(ref _task, value);
                IsSearching = value?.Status == "searching";
                InProgress = value?.Status == "in_progress";
            }
        }

        private bool _inprogress;
        public bool InProgress
        {
            get => _inprogress;
            set => SetField(ref _inprogress, value);
        }

        private bool _issearching;
        public bool IsSearching
        {
            get => _issearching;
            set => SetField(ref _issearching, value);
        }

        public bool IsOwner
        {
            get => _isOwner;
            set
            {
                if (SetField(ref _isOwner, value))
                    RefreshConditionalCommands();
            }
        }

        public bool HasVolunteered
        {
            get => _hasVolunteered;
            set => SetField(ref _hasVolunteered, value);
        }

        public bool CanVolunteer
        {
            get => _canVolunteer;
            set => SetField(ref _canVolunteer, value);
        }

        public string VolunteerStatus
        {
            get => _volunteerStatus;
            set => SetField(ref _volunteerStatus, value);
        }

        public ICommand EditCommand { get; }
        public ICommand DeleteCommand { get; }
        public ICommand GoToProfileCommand { get; }
        public ICommand VolunteerCommand { get; }
        public ICommand AcceptVolunteerCommand { get; }
        public ICommand RejectVolunteerCommand { get; }
        public ICommand StartTaskCommand { get; }
        public ICommand CompleteTaskCommand { get; }

        public TaskDetailViewModel(ITaskService taskService, IUserService userService, IServiceProvider serviceProvider)
        {
            _taskService = taskService;
            _userService = userService;
            _serviceProvider = serviceProvider;
            PageTitle = "Задача";

            EditCommand = new Command(async () => await ExecuteAsync(EditAsync, EditCommand));
            DeleteCommand = new Command(async () => await ExecuteAsync(DeleteAsync, DeleteCommand));
            GoToProfileCommand = new Command<int>(async (id) => await ExecuteAsync(() => GoToProfileAsync(id), GoToProfileCommand));
            VolunteerCommand = new Command(async () => await ExecuteAsync(VolunteerAsync, VolunteerCommand));
            AcceptVolunteerCommand = new Command<int>(async (volunteerId) => await ExecuteAsync(() => AcceptVolunteerAsync(volunteerId), AcceptVolunteerCommand));
            RejectVolunteerCommand = new Command<int>(async (volunteerId) => await ExecuteAsync(() => RejectVolunteerAsync(volunteerId), RejectVolunteerCommand));
            StartTaskCommand = new Command(async () => await ExecuteAsync(StartTaskAsync, StartTaskCommand),
                () => IsOwner && Task?.Status == "searching" && !IsBusy);
            CompleteTaskCommand = new Command(async () => await ExecuteAsync(CompleteTaskAsync, CompleteTaskCommand),
                () => IsOwner && Task?.Status == "in_progress" && !IsBusy);
            GoBackCommand = new Command(async () => await ExecuteAsync(GoBackAsync, GoBackCommand));
        }

        public Task InitializationTask { get; private set; }
        public void ApplyQueryAttributes(IDictionary<string, object> query)
        {
            if (query.TryGetValue("id", out var idObj) && idObj is int id)
            {
                taskId = id;
                InitializationTask = InitializeAsync(taskId);
            }
        }

        private async Task InitializeAsync(int taskId)
        {
            await ExecuteAsync(async () =>
            {
                if (_isInitialized) return;

                var taskResponse = await _taskService.GetTaskAsync(taskId);
                if (taskResponse.result is not true)
                    throw new Exception(taskResponse.message);

                Task = taskResponse.Data;
                PageTitle = Task.Title;
                IsOwner = _userService.CurrentUser?.Id == Task.CreatorId;

                if (IsOwner)
                {
                    CanVolunteer = false;
                    await LoadVolunteersAsync();
                }
                else
                {
                    var statusResponse = await _taskService.GetMyVolunteerStatusAsync(taskId);
                    if (statusResponse.result is not true)
                        throw new Exception(statusResponse.message);

                    switch (statusResponse.Data)
                    {
                        case "pending":
                            CanVolunteer = false; HasVolunteered = true; VolunteerStatus = "Ожидание ответа"; break;
                        case "accepted":
                            CanVolunteer = false; HasVolunteered = true; VolunteerStatus = "Вас приняли"; break;
                        case "rejected":
                            CanVolunteer = false; HasVolunteered = true; VolunteerStatus = "Вам отказали"; break;
                        case "cancelled":
                            CanVolunteer = false; HasVolunteered = true; VolunteerStatus = "Отменено"; break;
                        case "completed":
                            CanVolunteer = false; HasVolunteered = true; VolunteerStatus = "Задача завершена"; break;
                        default:
                            CanVolunteer = true; HasVolunteered = false; VolunteerStatus = ""; break;
                    }
                }

                _isInitialized = true;
            }, StartTaskCommand, CompleteTaskCommand);
        }

        private async Task LoadVolunteersAsync()
        {
            var volunteers = await _taskService.GetTaskVolunteersAsync(Task.Id);
            Volunteers.Clear();
            if (volunteers.Data != null)
            {
                foreach (var v in volunteers.Data)
                    Volunteers.Add(v);
            }
        }

        private async Task VolunteerAsync()
        {
            if (HasVolunteered) return;

            var success = await _taskService.VolunteerForTaskAsync(Task.Id);
            if (success.result is not true)
                throw new Exception(success.message ?? "Не удалось откликнуться");

            CanVolunteer = false;
            HasVolunteered = true;
            VolunteerStatus = "Ожидание ответа";
            await Application.Current.MainPage.DisplayAlert("Успех", "Вы откликнулись на задачу", "OK");
        }

        private async Task AcceptVolunteerAsync(int volunteerId)
        {
            var success = await _taskService.AcceptVolunteerAsync(Task.Id, volunteerId);
            if (success.result is not true)
                throw new Exception(success.message ?? "Не удалось принять");

            await LoadVolunteersAsync();
            RefreshConditionalCommands();
        }

        private async Task RejectVolunteerAsync(int volunteerId)
        {
            var success = await _taskService.RejectVolunteerAsync(Task.Id, volunteerId);
            if (success.result is not true)
                throw new Exception(success.message ?? "Не удалось отклонить");

            await LoadVolunteersAsync();
        }

        private async Task StartTaskAsync()
        {
            var success = await _taskService.StartTaskAsync(Task.Id);
            if (success.result is not true)
                throw new Exception(success.message ?? "Не удалось начать задачу");

            Task.Status = "in_progress";
            RefreshConditionalCommands();
            await Application.Current.MainPage.DisplayAlert("Успех", "Задача начата", "OK");
        }

        private async Task CompleteTaskAsync()
        {
            var confirm = await Application.Current.MainPage.DisplayAlert("Завершение", "Вы уверены, что задача выполнена?", "Да", "Нет");
            if (!confirm) return;

            var success = await _taskService.CompleteTaskAsync(Task.Id);
            if (success.result is not true)
                throw new Exception(success.message ?? "Не удалось завершить задачу");

            Task.Status = "completed";
            RefreshConditionalCommands();
            await Application.Current.MainPage.DisplayAlert("Успех", "Задача завершена, награда начислена волонтёрам", "OK");
        }

        private void RefreshConditionalCommands()
        {
            (StartTaskCommand as Command)?.ChangeCanExecute();
            (CompleteTaskCommand as Command)?.ChangeCanExecute();
        }

        private async Task EditAsync() { /* ... */ }
        private async Task DeleteAsync() { /* ... */ }
        private async Task GoToProfileAsync(int id)
        {
            await Shell.Current.GoToAsync(nameof(ProfilePage), new Dictionary<string, object?> { { "id", id } });
        }

        private bool _isInitialized;
    }
}