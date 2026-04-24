using System.Collections.ObjectModel;
using System.Threading.Tasks;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;

using nearby.Interfaces;
using nearby.Models;
using nearby.Services;
using nearby.Views.Main;

namespace nearby.ViewModels
{
    [QueryProperty(nameof(TaskId), "id")]
    public partial class TaskDetailViewModel : BaseViewModel
    {
        private readonly ITaskService _taskService;
        private readonly IUserService _userService;

        [ObservableProperty]
        private int _taskId;

        [ObservableProperty]
        private TaskItem _task = null!;

        [ObservableProperty]
        private bool _isOwner;

        [ObservableProperty]
        private bool _hasVolunteered;

        [ObservableProperty]
        private bool _canVolunteer;

        [ObservableProperty]
        private string _volunteerStatus = string.Empty;

        [ObservableProperty]
        private ObservableCollection<TaskVolunteerInfo> _volunteers = new();

        [ObservableProperty]
        private bool _inProgress;

        [ObservableProperty]
        private bool _isSearching;

        // Флаг, чтобы не инициализировать повторно
        private bool _isInitialized;

        public TaskDetailViewModel(ITaskService taskService, IUserService userService)
        {
            _taskService = taskService;
            _userService = userService;
        }

        partial void OnTaskIdChanged(int value)
        {
            if (value > 0)
                _ = InitializeAsync(value);
        }

        partial void OnTaskChanged(TaskItem? value)
        {
            if (value == null) return;
            PageTitle = value.Title;
            IsSearching = value.Status == "searching";
            InProgress = value.Status == "in_progress";
        }

        partial void OnIsOwnerChanged(bool value) => RefreshCommands();

        private async Task InitializeAsync(int taskId)
        {
            if (_isInitialized) return;
            IsBusy = true;
            try
            {
                var taskResponse = await _taskService.GetTaskAsync(taskId);
                if (taskResponse.result != true)
                    throw new Exception(taskResponse.message ?? "Ошибка загрузки задачи");

                Task = taskResponse.Data!;
                IsOwner = _userService.CurrentUser?.Id == Task.CreatorId;

                if (IsOwner)
                {
                    CanVolunteer = false;
                    await LoadVolunteersAsync();
                }
                else
                {
                    await LoadMyVolunteerStatusAsync(taskId);
                }
                _isInitialized = true;
            }
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
                await GoBackCommand.ExecuteAsync(null);
            }
            finally
            {
                IsBusy = false;
            }
        }

        private async Task LoadVolunteersAsync()
        {
            var response = await _taskService.GetTaskVolunteersAsync(Task.Id);
            if (response.result != true) return;

            Volunteers.Clear();
            if (response.Data != null)
            {
                foreach (var v in response.Data)
                    Volunteers.Add(v);
            }
        }

        private async Task LoadMyVolunteerStatusAsync(int taskId)
        {
            var response = await _taskService.GetMyVolunteerStatusAsync(taskId);
            if (response.result != true) return;

            (CanVolunteer, HasVolunteered, VolunteerStatus) = response.Data switch
            {
                "pending" => (false, true, "Ожидание ответа"),
                "accepted" => (false, true, "Вас приняли"),
                "rejected" => (false, true, "Вам отказали"),
                "cancelled" => (false, true, "Отменено"),
                "completed" => (false, true, "Задача завершена"),
                _ => (true, false, "")
            };
        }

        [RelayCommand(CanExecute = nameof(CanVolunteerExecute))]
        private async Task VolunteerAsync()
        {
            var response = await _taskService.VolunteerForTaskAsync(Task.Id);
            if (response.result != true)
                throw new Exception(response.message ?? "Не удалось откликнуться");

            CanVolunteer = false;
            HasVolunteered = true;
            VolunteerStatus = "Ожидание ответа";
            await ShowMsgAsync("Успех", "Вы откликнулись на задачу", "OK");
        }
        private bool CanVolunteerExecute() => CanVolunteer && !IsBusy;

        [RelayCommand(CanExecute = nameof(CanAcceptRejectExecute))]
        private async Task AcceptVolunteerAsync(int volunteerId)
        {
            var response = await _taskService.AcceptVolunteerAsync(Task.Id, volunteerId);
            if (response.result != true)
                throw new Exception(response.message ?? "Не удалось принять волонтёра");

            await LoadVolunteersAsync();
        }

        [RelayCommand(CanExecute = nameof(CanAcceptRejectExecute))]
        private async Task RejectVolunteerAsync(int volunteerId)
        {
            var response = await _taskService.RejectVolunteerAsync(Task.Id, volunteerId);
            if (response.result != true)
                throw new Exception(response.message ?? "Не удалось отклонить волонтёра");

            await LoadVolunteersAsync();
        }
        private bool CanAcceptRejectExecute() => IsOwner && !IsBusy;

        [RelayCommand(CanExecute = nameof(CanStartTaskExecute))]
        private async Task StartTaskAsync()
        {
            var response = await _taskService.StartTaskAsync(Task.Id);
            if (response.result != true)
                throw new Exception(response.message ?? "Не удалось начать задачу");

            Task.Status = "in_progress";
            await ShowMsgAsync("Успех", "Задача начата", "OK");
        }
        private bool CanStartTaskExecute() => IsOwner && Task?.Status == "searching" && !IsBusy;

        [RelayCommand(CanExecute = nameof(CanCompleteTaskExecute))]
        private async Task CompleteTaskAsync()
        {
            var confirm = await Application.Current!.MainPage!.DisplayAlert(
                "Завершение", "Вы уверены, что задача выполнена?", "Да", "Нет");
            if (!confirm) return;

            var response = await _taskService.CompleteTaskAsync(Task.Id);
            if (response.result != true)
                throw new Exception(response.message ?? "Не удалось завершить задачу");

            Task.Status = "completed";
            await ShowMsgAsync("Успех", "Задача завершена, награда начислена", "OK");
        }
        private bool CanCompleteTaskExecute() => IsOwner && Task?.Status == "in_progress" && !IsBusy;

        [RelayCommand]
        private async Task EditAsync()
        {
            // Реализация редактирования
        }

        [RelayCommand(CanExecute = nameof(CanDeleteTaskExecute))]
        private async Task DeleteAsync()
        {
            var confirm = await Application.Current!.MainPage!.DisplayAlert(
                "Удаление", "Удалить задачу?", "Да", "Нет");
            if (!confirm) return;

            var response = await _taskService.DeleteTaskAsync(Task.Id);
            if (response.result != true)
                throw new Exception(response.message ?? "Ошибка удаления");

            await ShowMsgAsync("Успех", "Задача удалена", "OK");
            await GoBackCommand.ExecuteAsync(null);
        }
        private bool CanDeleteTaskExecute() => IsOwner && !IsBusy;

        [RelayCommand]
        private async Task GoToProfileAsync(int userId)
        {
            await Shell.Current.GoToAsync(nameof(ProfilePage), new Dictionary<string, object?> { { "id", userId } });
        }

        private void RefreshCommands()
        {
            StartTaskCommand.NotifyCanExecuteChanged();
            CompleteTaskCommand.NotifyCanExecuteChanged();
            AcceptVolunteerCommand.NotifyCanExecuteChanged();
            RejectVolunteerCommand.NotifyCanExecuteChanged();
            DeleteCommand.NotifyCanExecuteChanged();
            VolunteerCommand.NotifyCanExecuteChanged();
        }
        protected override void OnBusyStateChanged(bool isBusy)
        {
            base.OnBusyStateChanged(isBusy);
            RefreshCommands();
        }
    }
}