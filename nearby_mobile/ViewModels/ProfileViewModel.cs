using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Diagnostics;
using System.Runtime.CompilerServices;
using System.Threading.Tasks;
using System.Windows.Input;
using nearby_mobile.Classes;
using nearby_mobile.Interfaces;
using nearby_mobile.Models;
using nearby_mobile.Services;
using nearby_mobile.Views;

namespace nearby_mobile.ViewModels
{
    public enum TaskCategory
    {
        Created,      // задачи, созданные пользователем
        InProgress,   // задачи, которые пользователь взял в работу
        Completed     // выполненные задачи
    }
    public class ProfileViewModel : BaseViewModel, INotifyPropertyChanged, IDisposable
    {
        private readonly IUserService _userService;
        private readonly IAuthService _authService;
        private readonly ITaskService _taskService;
        private readonly IServiceProvider _serviceProvider;

        private User? _user;
        private bool _isOwnProfile;
        public bool IsOwnProfile
        {
            get => _isOwnProfile;
            set => SetField(ref _isOwnProfile, value);
        }
        private bool _isInitialized;
        public bool IsInitialized
        {
            get => _isInitialized;
            set => SetField(ref _isInitialized, value);
        }

        private ObservableCollection<TaskItem> _userTasks = new();
        public ObservableCollection<TaskItem> UserTasks
        {
            get => _userTasks;
            set => SetField(ref _userTasks, value);
        }

        private TaskCategory _selectedCategory;
        public TaskCategory SelectedCategory
        {
            get => _selectedCategory;
            set
            {
                if (SetField(ref _selectedCategory, value))
                {
                    _currentTaskPage = 1;
                    _hasMoreTasks = true;
                    LoadUserTasksCommand.Execute(null);
                }
            }
        }

        private int _currentTaskPage = 1;
        private bool _hasMoreTasks = true;
        private bool _isLoadingTasks;
        private const int TaskPageSize = 10;

        public ICommand SelectCategoryCommand { get; }
        public ICommand LoadUserTasksCommand { get; }
        public ICommand TaskSelectedCommand { get; }

        public ProfileViewModel(
            IUserService userService,
            IAuthService authService,
            ITaskService taskService,
            IServiceProvider serviceProvider)
        {
            _userService = userService;
            _authService = authService;
            _taskService = taskService;
            _serviceProvider = serviceProvider;

            LogoutCommand = new Command(async () => await LogoutAsync(), () => IsOwnProfile);
            GoToEditCommand = new Command(async () => await GoToEditAsync(), () => IsOwnProfile);

            SelectCategoryCommand = new Command<TaskCategory>(category => SelectedCategory = category);
            LoadUserTasksCommand = new Command(async () => await LoadUserTasksAsync(reset: true));
            TaskSelectedCommand = new Command<TaskItem>(async (task) => await GoToTaskDetailAsync(task));

            _userService.PropertyChanged += OnUserServicePropertyChanged;
        }



        public async Task InitializeAsync(int? userId = null)
        {
            if (userId == null || userId == _userService.CurrentUser?.Id)
            {
                IsOwnProfile = true;
                _user = _userService.CurrentUser;
            }
            else
            {
                IsOwnProfile = false;
                _user = await _userService.LoadUserByIdAsync(userId.Value);
            }
            UpdateFromUser();
            RefreshCommands();
            await LoadUserTasksAsync(reset: false);
            SelectedCategory = TaskCategory.Created;
            _isInitialized = true;
        }

        private async Task LoadUserTasksAsync(bool reset)
        {
            if (_isLoadingTasks || _user == null) return;

            if (reset)
            {
                _currentTaskPage = 1;
                _hasMoreTasks = true;
                await MainThread.InvokeOnMainThreadAsync(() => UserTasks.Clear());
            }

            if (!_hasMoreTasks) return;

            _isLoadingTasks = true;

            try
            {
                string status = SelectedCategory switch
                {
                    TaskCategory.Created => "searching",
                    TaskCategory.InProgress => "in_progress",
                    TaskCategory.Completed => "completed",
                    _ => ""
                };

                var tasks = await _taskService.GetUserTasksAsync(_user.Id, status, _currentTaskPage, TaskPageSize);

                if (tasks.Any())
                {
                    await MainThread.InvokeOnMainThreadAsync(() =>
                    {
                        foreach (var task in tasks)
                            UserTasks.Add(task);
                    });
                    _currentTaskPage++;
                    if (tasks.Count < TaskPageSize)
                        _hasMoreTasks = false;
                }
                else
                {
                    _hasMoreTasks = false;
                }
            }
            finally
            {
                _isLoadingTasks = false;
            }
        }

        private async Task GoToTaskDetailAsync(TaskItem task)
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

        private void OnUserServicePropertyChanged(object? sender, PropertyChangedEventArgs e)
        {
            if (e.PropertyName == nameof(IUserService.CurrentUser))
            {
                _user = _userService.CurrentUser;
                UpdateFromUser();
            }
        }

        public void UserChanged()
        {
            if (_isOwnProfile)
            {
                _user = _userService.CurrentUser;
                UpdateFromUser();
            }
        }

        private void UpdateFromUser()
        {
            if (_user != null)
            {
                FullName = _user.FullName;
                Phone = _user.Phone ?? "";
                Email = _user.Email ?? "";
                BirthDate = _user.BirthDate?.ToString("dd.MM.yyyy") ?? "";
                About = _user.About ?? "";
            }
        }

        private void RefreshCommands()
        {
            (LogoutCommand as Command)?.ChangeCanExecute();
            (GoToEditCommand as Command)?.ChangeCanExecute();
        }

        private string _name;
        public string Name
        {
            get => _name;
            set => SetField(ref _name, value);
        }

        private string _familia;
        public string Familia
        {
            get => _familia;
            set => SetField(ref _familia, value);
        }

        private string _otchestvo;
        public string Otchestvo
        {
            get => _otchestvo;
            set => SetField(ref _otchestvo, value);
        }

        private string _fullName;
        public string FullName
        {
            get => _fullName;
            set
            {
                if (SetField(ref _fullName, value) && !string.IsNullOrEmpty(value))
                {
                    var parts = value.Split(' ');
                    Familia = parts.Length > 0 ? parts[0] : "";
                    Name = parts.Length > 1 ? parts[1] : "";
                    Otchestvo = parts.Length > 2 ? parts[2] : "";
                }
            }
        }

        private string _phone;
        public string Phone
        {
            get => _phone;
            set => SetField(ref _phone, value);
        }

        private string _email;
        public string Email
        {
            get => _email;
            set => SetField(ref _email, value);
        }

        private string _birthDate;
        public string BirthDate
        {
            get => _birthDate;
            set => SetField(ref _birthDate, value);
        }

        private string _about;
        public string About
        {
            get => _about;
            set => SetField(ref _about, value);
        }

        private string _education = "Пермский авиационный техникум...";
        public string Education
        {
            get => _education;
            set => SetField(ref _education, value);
        }

        public ICommand LogoutCommand { get; }
        public ICommand GoToEditCommand { get; }

        private async Task LogoutAsync()
        {
            if (!IsOwnProfile) return;
            await _authService.LogoutAsync();
            _userService.CurrentUser = null;

            // Заменяем MainPage на LoginPage (обёрнутую в NavigationPage)
            Application.Current.MainPage = new NavigationPage(_serviceProvider.GetRequiredService<LoginPage>());
        }

        private async Task GoToEditAsync()
        {
            if (!IsOwnProfile) return;
            var page = _serviceProvider.GetRequiredService<EditProfilePage>();
            var vm = _serviceProvider.GetRequiredService<EditProfileViewModel>();
            page.BindingContext = vm;
            await Application.Current.MainPage.Navigation.PushModalAsync(page);
        }

        public void Dispose()
        {
            _userService.PropertyChanged -= OnUserServicePropertyChanged;
        }
    }
}