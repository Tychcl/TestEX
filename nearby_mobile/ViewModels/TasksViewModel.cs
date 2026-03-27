using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Diagnostics;
using System.Runtime.CompilerServices;
using System.Windows.Input;
using nearby_mobile.Classes;
using nearby_mobile.Interfaces;
using nearby_mobile.Models;
using nearby_mobile.Views;

namespace nearby_mobile.ViewModels;

public class TasksViewModel : BaseViewModel, INotifyPropertyChanged
{
    private readonly ITaskService _taskService;
    private readonly IServiceProvider _serviceProvider;

    private ObservableCollection<TaskItem> _tasks = new ObservableCollection<TaskItem>();
    private bool _isRefreshing;
    private string _statusFilter;
    private string _priorityFilter;
    private string _cityFilter;
    private int _currentPage = 1;
    private bool _hasMorePages = true;
    private bool _isLoading;
    private const int PageSize = 10;

    public ObservableCollection<TaskItem> Tasks
    {
        get => _tasks;
        set { 
            _tasks = value; 
            SetField(ref _tasks, value); }
    }

    public bool IsRefreshing
    {
        get => _isRefreshing;
        set { _isRefreshing = value; SetField(ref _isRefreshing, value); }
    }

    public string StatusFilter
    {
        get => _statusFilter;
        set { _statusFilter = value; _currentPage = 1; LoadTasksCommand.Execute(null); }
    }

    public string PriorityFilter
    {
        get => _priorityFilter;
        set { _priorityFilter = value; _currentPage = 1; LoadTasksCommand.Execute(null); }
    }

    public string CityFilter
    {
        get => _cityFilter;
        set { _cityFilter = value; _currentPage = 1; LoadTasksCommand.Execute(null); }
    }

    public ICommand LoadTasksCommand { get; }
    public ICommand RefreshCommand { get; }
    public ICommand LoadMoreCommand { get; }
    public ICommand TaskSelectedCommand { get; }
    public ICommand GoToCreateTaskCommand { get; }
    public ICommand DeleteTaskCommand { get; }

    public TasksViewModel(ITaskService taskService, IServiceProvider serviceProvider)
    {
        _taskService = taskService;
        _serviceProvider = serviceProvider;

        LoadTasksCommand = new Command(async () => await LoadTasksAsync(reset: true));
        RefreshCommand = new Command(async () => await LoadTasksAsync(reset: true));
        LoadMoreCommand = new Command(async () => await LoadTasksAsync(reset: false));
        TaskSelectedCommand = new Command<TaskItem>(async (task) => await GoToDetailAsync(task));
        GoToCreateTaskCommand = new Command(async () => await GoToCreateAsync());
        DeleteTaskCommand = new Command<TaskItem>(async (task) => await DeleteTaskAsync(task));
    }

    private async Task LoadTasksAsync(bool reset)
    {
        if (_isLoading) return;

        if (reset)
        {
            _currentPage = 1;
            _hasMorePages = true;
            await MainThread.InvokeOnMainThreadAsync(() => Tasks.Clear());
        }

        if (!_hasMorePages) return;

        _isLoading = true;
        IsRefreshing = true;

        try
        {
            var tasks = await _taskService.GetTasksAsync(
                _currentPage,
                PageSize,
                StatusFilter,
                PriorityFilter,
                CityFilter);

            if (tasks.Any())
            {
                await MainThread.InvokeOnMainThreadAsync(() =>
                {
                    foreach (var task in tasks)
                        Tasks.Add(task);
                });
                _currentPage++;

                if (tasks.Count < PageSize)
                    _hasMorePages = false;
            }
            else
            {
                _hasMorePages = false;
            }
        }
        finally
        {
            _isLoading = false;
            IsRefreshing = false;
        }
    }

    private async Task GoToDetailAsync(TaskItem task)
    {
        try
        {
            var detailPage = _serviceProvider.GetRequiredService<TaskDetailPage>();
            var vm = _serviceProvider.GetRequiredService<TaskDetailViewModel>();
            await vm.InitializeAsync(task.Id);
            detailPage.BindingContext = vm;
            await Application.Current.MainPage.Navigation.PushModalAsync(detailPage);
        }
        catch
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось открыть задачу", "OK");
        }
    }

    private async Task GoToCreateAsync()
    {
        await Shell.Current.GoToAsync(nameof(AddEditTaskPage));
    }

    private async Task DeleteTaskAsync(TaskItem task)
    {
        var confirm = await Application.Current.MainPage.DisplayAlert("Удаление", $"Удалить задачу \"{task.Title}\"?", "Да", "Нет");
        if (!confirm) return;

        var success = await _taskService.DeleteTaskAsync(task.Id);
        if (success)
        {
            Tasks.Remove(task);
            await Application.Current.MainPage.DisplayAlert("Успех", "Задача удалена", "OK");
        }
        else
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось удалить задачу", "OK");
        }
    }

}