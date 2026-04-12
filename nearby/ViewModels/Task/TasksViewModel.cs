using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Windows.Input;
using nearby.Classes;
using nearby.Interfaces;
using nearby.Models;
using nearby.Views.Additional;


namespace nearby.ViewModels
{
    public class TasksViewModel : BaseViewModel, INotifyPropertyChanged
    {
        private readonly ITaskService _taskService;
        private readonly IServiceProvider _serviceProvider;

        private ObservableCollection<TaskItem> _tasks = new();
        private bool _isRefreshing;
        private string _statusFilter;
        private string _priorityFilter;
        private string _cityFilter;
        private int _currentPage = 1;
        private bool _hasMorePages = true;
        private const int PageSize = 10;

        public ObservableCollection<TaskItem> Tasks
        {
            get => _tasks;
            set => SetField(ref _tasks, value);
        }

        public bool IsRefreshing
        {
            get => _isRefreshing;
            set => SetField(ref _isRefreshing, value);
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

            LoadTasksCommand = new Command(async () => await ExecuteAsync(() => LoadTasksAsync(reset: true), LoadTasksCommand));
            RefreshCommand = new Command(async () => await ExecuteAsync(() => LoadTasksAsync(reset: true), RefreshCommand), () => !IsBusy);
            LoadMoreCommand = new Command(async () => await ExecuteAsync(() => LoadTasksAsync(reset: false), LoadMoreCommand), () => !IsBusy && _hasMorePages);
            TaskSelectedCommand = new Command<TaskItem>(async (task) => await ExecuteAsync(() => GoToDetailAsync(task), TaskSelectedCommand));
            GoToCreateTaskCommand = new Command(async () => await ExecuteAsync(GoToCreateAsync, GoToCreateTaskCommand));
            DeleteTaskCommand = new Command<TaskItem>(async (task) => await ExecuteAsync(() => DeleteTaskAsync(task), DeleteTaskCommand));
        }

        private async Task LoadTasksAsync(bool reset)
        {
            if (reset)
            {
                _currentPage = 1;
                _hasMorePages = true;
                Tasks.Clear();
            }

            if (!_hasMorePages) return;

            IsRefreshing = true;
            try
            {
                var response = await _taskService.GetTasksAsync(_currentPage, PageSize, StatusFilter, PriorityFilter, CityFilter);
                if (response.result is not true)
                    throw new Exception(response.message);

                if (response.Object != null && response.Object.Any())
                {
                    foreach (var task in response.Object)
                        Tasks.Add(task);
                    _currentPage++;
                    if (response.Object.Count < PageSize)
                        _hasMorePages = false;
                }
                else
                {
                    _hasMorePages = false;
                }
            }
            finally
            {
                IsRefreshing = false;
            }
        }

        private async Task GoToDetailAsync(TaskItem task)
        {
            await Shell.Current.GoToAsync(nameof(TaskDetailPage), new Dictionary<string, object?> { { "id", task.Id } });
        }

        private Task GoToCreateAsync()
        {
            // Реализация позже
            return Task.CompletedTask;
        }

        private async Task DeleteTaskAsync(TaskItem task)
        {
            var confirm = await Application.Current.MainPage.DisplayAlert("Удаление", $"Удалить задачу \"{task.Title}\"?", "Да", "Нет");
            if (!confirm) return;

            var success = await _taskService.DeleteTaskAsync(task.Id);
            if (success.result is not true)
                throw new Exception(success.message);

            Tasks.Remove(task);
            await Application.Current.MainPage.DisplayAlert("Успех", "Задача удалена", "OK");
        }
    }
}