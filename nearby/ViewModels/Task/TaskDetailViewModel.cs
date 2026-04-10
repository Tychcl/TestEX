using System;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Diagnostics;
using System.Runtime.CompilerServices;
using System.Threading.Tasks;
using System.Windows.Input;
using Microsoft.Maui;
using nearby.Classes;
using nearby.Interfaces;
using nearby.Models;
using nearby.Services;
using nearby.Views.Main;

namespace nearby.ViewModels;

[QueryProperty(nameof(taskId), "id")]
public class TaskDetailViewModel : BaseViewModel, INotifyPropertyChanged, IQueryAttributable
{
    public Task Initialization { get; private set; }
    public void ApplyQueryAttributes(IDictionary<string, object> query)
    {
        if (query.TryGetValue("id", out var idObj) && idObj is int id)
        {
            taskId = id;
            Initialization = InitializeAsync(taskId);
        }
    }

    #region services
    private readonly ITaskService _taskService;
    private readonly IUserService _userService;
    private readonly IServiceProvider _serviceProvider;
    #endregion

    #region variables
    private int taskId;

    private ObservableCollection<TaskVolunteerInfo> _volunteers = new();
    public ObservableCollection<TaskVolunteerInfo> Volunteers
    {
        get => _volunteers;
        set => SetField(ref _volunteers, value);
    }

    private TaskItem _task;
    public TaskItem Task
    {
        get => _task;
        set
        {
            SetField(ref _task, value);
            IsSearching = value.Status == "searching";
            InProgress = value.Status == "in_progress";
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
    #endregion

    #region Icommands
    public ICommand VolunteerCommand { get; }
    public ICommand AcceptVolunteerCommand { get; }
    public ICommand RejectVolunteerCommand { get; }
    public ICommand StartTaskCommand { get; }
    public ICommand CompleteTaskCommand { get; }
    public ICommand EditCommand { get; }
    public ICommand DeleteCommand { get; }
    public ICommand GoToProfileCommand { get; }
    #endregion

    private async Task GoToProfileAsync(int id)
    {
        await Shell.Current.GoToAsync(nameof(ProfilePage),new Dictionary<string, object?>(){ {"id", id} });
    }

    public TaskDetailViewModel(ITaskService taskService, IUserService userService, IServiceProvider serviceProvider)
    {
        _taskService = taskService;
        _userService = userService;
        _serviceProvider = serviceProvider;
        PageTitle = "Задача";

        EditCommand = new Command(async () => await EditAsync());
        DeleteCommand = new Command(async () => await DeleteAsync());
        GoToProfileCommand = new Command(async (id) => await GoToProfileAsync((int)id));
        VolunteerCommand = new Command(async () => await VolunteerAsync());
        AcceptVolunteerCommand = new Command<int>(async (volunteerId) => await AcceptVolunteerAsync(volunteerId));
        RejectVolunteerCommand = new Command<int>(async (volunteerId) => await RejectVolunteerAsync(volunteerId));
        StartTaskCommand = new Command(async () => await StartTaskAsync(), () => IsOwner && Task?.Status == "searching");
        CompleteTaskCommand = new Command(async () => await CompleteTaskAsync(), () => IsOwner && Task?.Status == "in_progress");
        GoBackCommand = new Command(async () => await GoBackAsync());
    }
    private bool _isInitialized;
    public bool IsInitialized { get => _isInitialized; }
    public async Task InitializeAsync(int taskId)
    {
        if (_isInitialized) return;
        var task = await _taskService.GetTaskAsync(taskId);
        if (task.result is not true)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", task.message, "OK");
        }
        if (task.Object != null)
        {
            Task = task.Object;
            PageTitle = Task.Title;
            IsOwner = _userService.CurrentUser?.Id == task.Object.CreatorId;

            if (IsOwner)
            {
                CanVolunteer = false;
                await LoadVolunteersAsync();
            }
            else
            {
                var myStatus = await _taskService.GetMyVolunteerStatusAsync(taskId);
                if (myStatus.result is not true)
                {
                    await Application.Current.MainPage.DisplayAlert("Ошибка", task.message, "OK");
                }
                switch (myStatus.Object)
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
        Volunteers.Clear();
        if (volunteers.Object is null) return;
        foreach (var v in volunteers.Object)
            Volunteers.Add(v);
    }

    private async Task VolunteerAsync()
    {
        if (HasVolunteered) return;
        var success = await _taskService.VolunteerForTaskAsync(Task.Id);
        if (success.result is not true)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось откликнуться", "OK");
        }
        if (success.result is true)
        {
            CanVolunteer = false;
            HasVolunteered = true;
            VolunteerStatus = "Ожидание ответа";
            await Application.Current.MainPage.DisplayAlert("Успех", "Вы откликнулись на задачу", "OK");
        }
    }

    private async Task AcceptVolunteerAsync(int volunteerId)
    {
        var success = await _taskService.AcceptVolunteerAsync(Task.Id, volunteerId);
        if (success.result is not true)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось принять", "OK");
        }
        if (success.result is true)
        {
            var v = Volunteers.FirstOrDefault(x => x.Id == volunteerId);
            v.pending = false;
            v.accepted = true;
            v.rejected = false;
            await LoadVolunteersAsync();
            RefreshCommands();
        }
    }

    private async Task RejectVolunteerAsync(int volunteerId)
    {
        var success = await _taskService.RejectVolunteerAsync(Task.Id, volunteerId);
        if (success.result is not true)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось отклонить", "OK");
        }
        if (success.result is true)
        {
            var v = Volunteers.FirstOrDefault(x => x.Id == volunteerId);
            v.pending = false;
            v.accepted = false;
            v.rejected = true;
            await LoadVolunteersAsync();
        }
    }

    private async Task StartTaskAsync()
    {
        var success = await _taskService.StartTaskAsync(Task.Id);
        if (success.result is not true)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось начать задачу\nВозможно волонтеры еще не найдены", "OK");
        }
        if (success.result is true)
        {
            Task.Status = "in_progress";
            RefreshCommands();
            await Application.Current.MainPage.DisplayAlert("Успех", "Задача начата", "OK");
        }
    }

    private async Task CompleteTaskAsync()
    {
        var confirm = await Application.Current.MainPage.DisplayAlert("Завершение", "Вы уверены, что задача выполнена?", "Да", "Нет");
        if (!confirm) return;

        var success = await _taskService.CompleteTaskAsync(Task.Id);
        if (success.result is not true)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось завершить задачу", "OK");
        }
        if (success.result is true)
        {
            Task.Status = "completed";
            RefreshCommands();
            await Application.Current.MainPage.DisplayAlert("Успех", "Задача завершена, награда начислена волонтёрам", "OK");
        }
    }

    private void RefreshCommands()
    {
        (StartTaskCommand as Command)?.ChangeCanExecute();
        (CompleteTaskCommand as Command)?.ChangeCanExecute();
    }

    private async Task EditAsync()
    {
        //await Shell.Current.GoToAsync(nameof(AddEditTaskPage), new Dictionary<string, object?>() { { "id", task.Id } });
    }

    private async Task DeleteAsync()
    {
        var confirm = await Application.Current.MainPage.DisplayAlert("Удаление", "Вы уверены?", "Да", "Нет");
        if (!confirm) return;

        var success = await _taskService.DeleteTaskAsync(Task.Id);
        if (success.result is not true)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось удалить задачу", "OK");
        }
        if (success.result is true)
        {
            await Application.Current.MainPage.DisplayAlert("Успех", "Задача удалена", "OK");
            await Shell.Current.Navigation.PopAsync();
        }
    }

}