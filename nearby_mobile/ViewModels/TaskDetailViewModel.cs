using System.ComponentModel;
using System.Diagnostics;
using System.Runtime.CompilerServices;
using System.Windows.Input;
using nearby_mobile.Interfaces;
using nearby_mobile.Models;
using nearby_mobile.Services;
using nearby_mobile.Views;

namespace nearby_mobile.ViewModels;

public class TaskDetailViewModel : INotifyPropertyChanged
{
    private readonly ITaskService _taskService;
    private readonly IUserService _userService;
    private readonly IServiceProvider _serviceProvider;

    private TaskItem _task;
    private bool _isOwner;

    public TaskItem Task
    {
        get => _task;
        set { _task = value; OnPropertyChanged(); }
    }

    public bool IsOwner
    {
        get => _isOwner;
        set { _isOwner = value; OnPropertyChanged(); }
    }

    public ICommand EditCommand { get; }
    public ICommand DeleteCommand { get; }

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

        EditCommand = new Command(async () => await EditAsync());
        DeleteCommand = new Command(async () => await DeleteAsync());
        GoToAuthorProfileCommand = new Command(async () => await GoToAuthorProfileAsync());
    }

    public async Task InitializeAsync(int taskId)
    {
        var task = await _taskService.GetTaskAsync(taskId);
        if (task != null)
        {
            Task = task;
            IsOwner = _userService.CurrentUser?.Id == task.CreatorId;
        }
    }

    private async Task EditAsync()
    {
        var editPage = _serviceProvider.GetRequiredService<AddEditTaskPage>();
        var viewModel = _serviceProvider.GetRequiredService<AddEditTaskViewModel>();
        await viewModel.InitializeForEditAsync(Task.Id);
        editPage.BindingContext = viewModel;
        await Shell.Current.Navigation.PushAsync(editPage);
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

    public event PropertyChangedEventHandler? PropertyChanged;
    protected virtual void OnPropertyChanged([CallerMemberName] string propertyName = null)
    {
        PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
    }
}