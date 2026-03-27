using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Diagnostics;
using System.Runtime.CompilerServices;
using System.Windows.Input;
using nearby_mobile.Interfaces;
using nearby_mobile.Models;
using nearby_mobile.Services;
using nearby_mobile.Views;
using nearby_mobile.Classes;

namespace nearby_mobile.ViewModels;

public class TaskDetailViewModel : BaseViewModel, INotifyPropertyChanged, IQueryAttributable
{
    private int _taskId;
    public void ApplyQueryAttributes(IDictionary<string, object> query)
    {
        if (query.TryGetValue("id", out var idObj) && idObj is int id)
        {
            _taskId = id;
            _ = InitializeAsync(_taskId);
        }
    }
    private string _pagetitle = string.Empty;
    public string PageTitle
    {
        get => _pagetitle;
        set { if (_pagetitle != value) { _pagetitle = value; SetField(ref _pagetitle, value); } }
    }
    private ObservableCollection<TaskVolunteerInfo> _volunteers = new();
    public ObservableCollection<TaskVolunteerInfo> Volunteers
    {
        get => _volunteers;
        set => SetField(ref _volunteers, value);
    }

    private bool _isOwner; // автор задачи
    public bool IsOwner
    {
        get => _isOwner;
        set => SetField(ref _isOwner, value);
    }

    private bool _hasVolunteered; // откликался ли текущий пользователь
    public bool HasVolunteered
    {
        get => _hasVolunteered;
        set => SetField(ref _hasVolunteered, value);
    }

    private bool _canvolunteer; // откликался ли текущий пользователь
    public bool CanVolunteer
    {
        get => _canvolunteer;
        set => SetField(ref _canvolunteer, value);
    }

    private string _volunteerStatus; // статус отклика текущего пользователя
    public string VolunteerStatus
    {
        get => _volunteerStatus;
        set => SetField(ref _volunteerStatus, value);
    }

    public ICommand VolunteerCommand { get; }
    public ICommand AcceptVolunteerCommand { get; }
    public ICommand RejectVolunteerCommand { get; }
    public ICommand StartTaskCommand { get; }
    public ICommand CompleteTaskCommand { get; }

    private readonly ITaskService _taskService;
    private readonly IUserService _userService;
    private readonly IServiceProvider _serviceProvider;

    private TaskItem _task;

    public TaskItem Task
    {
        get => _task;
        set { _task = value; SetField(ref _task, value); }
    }

    public ICommand EditCommand { get; }
    public ICommand DeleteCommand { get; }
    public ICommand GoBackCommand { get; }
    public ICommand GoToAuthorProfileCommand { get; }

    private async Task GoToAuthorProfileAsync()
    {
        if (_task?.CreatorId is null) return;

        var profilePage = _serviceProvider.GetRequiredService<ProfilePage>();
        await profilePage.InitializeAsync(_task.CreatorId);
        await Shell.Current.Navigation.PushAsync(profilePage);
    }

    public TaskDetailViewModel(ITaskService taskService, IUserService userService, IServiceProvider serviceProvider)
    {
        _taskService = taskService;
        _userService = userService;
        _serviceProvider = serviceProvider;
        PageTitle = "Задача";

        EditCommand = new Command(async () => await EditAsync());
        DeleteCommand = new Command(async () => await DeleteAsync());
        GoToAuthorProfileCommand = new Command(async () => await GoToAuthorProfileAsync());
        VolunteerCommand = new Command(async () => await VolunteerAsync());
        AcceptVolunteerCommand = new Command<int>(async (volunteerId) => await AcceptVolunteerAsync(volunteerId));
        RejectVolunteerCommand = new Command<int>(async (volunteerId) => await RejectVolunteerAsync(volunteerId));
        StartTaskCommand = new Command(async () => await StartTaskAsync(), () => IsOwner && Task?.Status == "searching");
        CompleteTaskCommand = new Command(async () => await CompleteTaskAsync(), () => IsOwner && Task?.Status == "in_progress");
        GoBackCommand = new Command(async () => await GoBackAsync());
    }
    private bool _isInitialized;
    public async Task InitializeAsync(int taskId)
    {
        if (_isInitialized) return;
        var task = await _taskService.GetTaskAsync(taskId);
        if (task != null)
        {
            Task = task;
            IsOwner = _userService.CurrentUser?.Id == task.CreatorId;

            if (IsOwner)
            {
                CanVolunteer = false;
                await LoadVolunteersAsync();
            }
            else
            {
                var myStatus = await _taskService.GetMyVolunteerStatusAsync(taskId);
                switch (myStatus)
                {
                    case "pending":
                        CanVolunteer = false;
                        HasVolunteered = true;
                        VolunteerStatus = "Ожидание ответа";
                        break;
                    case "accepted":
                        CanVolunteer = false;
                        HasVolunteered = true;
                        VolunteerStatus = "Вас приняли";
                        break;
                    case "rejected":
                        CanVolunteer = false;
                        HasVolunteered = true;
                        VolunteerStatus = "Вам отказали";
                        break;
                    case "cancelled":
                        CanVolunteer = false;
                        HasVolunteered = true;
                        VolunteerStatus = "Отменено";
                        break;
                    case "completed":
                        CanVolunteer = false;
                        HasVolunteered = true;
                        VolunteerStatus = "Задача завершена";
                        break;
                    default:
                        CanVolunteer = true;
                        HasVolunteered = false;
                        VolunteerStatus = "";
                        break;
                }
            }

            RefreshCommands();
        }
        _isInitialized = true;
    }

    private async Task LoadVolunteersAsync()
    {
        var volunteers = await _taskService.GetTaskVolunteersAsync(Task.Id);
        MainThread.BeginInvokeOnMainThread(() =>
        {
            Volunteers.Clear();
            foreach (var v in volunteers)
                Volunteers.Add(v);
        });
    }

    private async Task VolunteerAsync()
    {
        if (HasVolunteered) return;
        var success = await _taskService.VolunteerForTaskAsync(Task.Id);
        if (success)
        {
            CanVolunteer = false;
            HasVolunteered = true;
            VolunteerStatus = "Ожидание ответа";
            await Application.Current.MainPage.DisplayAlert("Успех", "Вы откликнулись на задачу", "OK");
        }
        else
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось откликнуться", "OK");
        }
    }

    private async Task AcceptVolunteerAsync(int volunteerId)
    {
        var success = await _taskService.AcceptVolunteerAsync(Task.Id, volunteerId);
        if (success)
        {
            var v = Volunteers.FirstOrDefault(x => x.Id == volunteerId);
            v.pending = false;
            v.accepted = true;
            v.rejected = false;
            await LoadVolunteersAsync();
            RefreshCommands();
        }
        else
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось принять", "OK");
        }
    }

    private async Task RejectVolunteerAsync(int volunteerId)
    {
        var success = await _taskService.RejectVolunteerAsync(Task.Id, volunteerId);
        if (success)
        {
            var v = Volunteers.FirstOrDefault(x => x.Id == volunteerId);
            v.pending = false;
            v.accepted = false;
            v.rejected = true;
            await LoadVolunteersAsync();
        }
        else
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось отклонить", "OK");
        }
    }

    private async Task StartTaskAsync()
    {
        var success = await _taskService.StartTaskAsync(Task.Id);
        if (success)
        {
            Task.Status = "in_progress";
            RefreshCommands();
            await Application.Current.MainPage.DisplayAlert("Успех", "Задача начата", "OK");
        }
        else
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось начать задачу\nВозможно волонтеры еще не найдены", "OK");
        }
    }

    private async Task CompleteTaskAsync()
    {
        var confirm = await Application.Current.MainPage.DisplayAlert("Завершение", "Вы уверены, что задача выполнена?", "Да", "Нет");
        if (!confirm) return;

        var success = await _taskService.CompleteTaskAsync(Task.Id);
        if (success)
        {
            Task.Status = "completed";
            RefreshCommands();
            await Application.Current.MainPage.DisplayAlert("Успех", "Задача завершена, награда начислена волонтёрам", "OK");
        }
        else
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось завершить задачу", "OK");
        }
    }

    private void RefreshCommands()
    {
        (StartTaskCommand as Command)?.ChangeCanExecute();
        (CompleteTaskCommand as Command)?.ChangeCanExecute();
    }

    private async Task EditAsync()
    {
        await Shell.Current.GoToAsync($"{nameof(AddEditTaskPage)}?id={Task.Id}");
    }

    private async Task DeleteAsync()
    {
        var confirm = await Application.Current.MainPage.DisplayAlert("Удаление", "Вы уверены?", "Да", "Нет");
        if (!confirm) return;

        var success = await _taskService.DeleteTaskAsync(Task.Id);
        if (success)
        {
            await Application.Current.MainPage.DisplayAlert("Успех", "Задача удалена", "OK");
            await Shell.Current.Navigation.PopAsync();
        }
        else
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось удалить задачу", "OK");
        }
    }

    private async Task GoBackAsync()
    {
        await Shell.Current.GoToAsync("..");
    }

}