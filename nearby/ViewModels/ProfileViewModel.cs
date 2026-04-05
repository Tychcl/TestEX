using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Windows.Input;
using nearby.Classes;
using nearby.Interfaces;
using nearby.Models;
using nearby.Services;
using nearby.Views.Additional;
using nearby.Views.Auth;

namespace nearby.ViewModels
{
    public enum TaskCategory
    {
        Created,
        InProgress,
        Completed
    }

    public class ProfileViewModel : BaseViewModel, INotifyPropertyChanged, IDisposable, IQueryAttributable
    {
        #region Services
        private readonly IUserService _userService;
        private readonly IAuthService _authService;
        private readonly ITaskService _taskService;
        private readonly IServiceProvider _serviceProvider;
        #endregion

        #region Fields
        private bool _loading;
        private const int TaskPageSize = 10;
        private int _currentTaskPage = 1;
        private bool _hasMoreTasks = true;
        private bool _isLoadingTasks;
        private int _userId = -1; // ID пользователя, чей профиль смотрим

        private TaskCategory _selectedCategory = TaskCategory.Created;
        public TaskCategory SelectedCategory
        {
            get => _selectedCategory;
            set
            {
                if (SetField(ref _selectedCategory, value))
                {
                    // При смене категории сбрасываем пагинацию и перезагружаем задачи
                    _currentTaskPage = 1;
                    _hasMoreTasks = true;
                    _ = LoadUserTasksAsync(reset: true);
                }
            }
        }

        private ObservableCollection<TaskItem> _userTasks = new();
        public ObservableCollection<TaskItem> UserTasks
        {
            get => _userTasks;
            set => SetField(ref _userTasks, value);
        }

        private User _user = null!;
        public User User
        {
            get => _user;
            set => SetField(ref _user, value);
        }

        private string _surname = string.Empty;
        public string Surname
        {
            get => _surname;
            set => SetField(ref _surname, value);
        }

        private string _name = string.Empty;
        public string Name
        {
            get => _name;
            set => SetField(ref _name, value);
        }

        private string _patronymic = string.Empty;
        public string Patronymic
        {
            get => _patronymic;
            set => SetField(ref _patronymic, value);
        }

        private string _phone = string.Empty;
        public string Phone
        {
            get => _phone;
            set => SetField(ref _phone, value);
        }

        private string _email = string.Empty;
        public string Email
        {
            get => _email;
            set => SetField(ref _email, value);
        }

        private string _birthDate = string.Empty;
        public string BirthDate
        {
            get => _birthDate;
            set => SetField(ref _birthDate, value);
        }

        private string _about = string.Empty;
        public string About
        {
            get => _about;
            set => SetField(ref _about, value);
        }

        private bool _isOwnProfile;
        public bool IsOwnProfile
        {
            get => _isOwnProfile;
            set => SetField(ref _isOwnProfile, value);
        }
        #endregion

        #region Commands
        public ICommand LogoutCommand { get; }
        public ICommand GoToEditCommand { get; }
        public ICommand SelectCategoryCommand { get; }
        public ICommand LoadUserTasksCommand { get; }
        #endregion

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

            _userService.PropertyChanged += OnUserServicePropertyChanged;

            LogoutCommand = new Command(async () => await LogoutAsync(), () => IsOwnProfile);
            GoToEditCommand = new Command(async () => await GoToEditAsync(), () => IsOwnProfile);
            SelectCategoryCommand = new Command<TaskCategory>(category => SelectedCategory = category);
            LoadUserTasksCommand = new Command(async () => await LoadUserTasksAsync(reset: true));
        }

        #region IQueryAttributable
        public async void ApplyQueryAttributes(IDictionary<string, object> query)
        {
            if (query.TryGetValue("id", out var idObj) && idObj is int id && id > 0)
            {
                _userId = id;
            }
            else
            {
                _userId = -1; // свой профиль
            }

            // Загружаем данные после получения параметров
            await LoadData();
        }
        #endregion

        #region Commands Implementation
        private async Task LogoutAsync()
        {
            if (!IsOwnProfile) return;
            await _authService.LogoutAsync();
            _userService.CurrentUser = null;
            Application.Current.MainPage = _serviceProvider.GetRequiredService<AuthShell>();
        }

        private async Task GoToEditAsync()
        {
            if (!IsOwnProfile) return;

            if (Shell.Current != null)
            {
                await Shell.Current.GoToAsync(nameof(EditProfilePage), true);
            }
            else
            {
                var page = _serviceProvider.GetRequiredService<EditProfilePage>();
                var vm = _serviceProvider.GetRequiredService<EditProfileViewModel>();
                page.BindingContext = vm;
                await Application.Current.MainPage.Navigation.PushModalAsync(page);
            }
        }

        public async Task LoadUserTasksAsync(bool reset)
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

                var response = await _taskService.GetUserTasksAsync(_user.Id, status, _currentTaskPage, TaskPageSize);
                if (response?.Object == null) return;

                var tasks = response.Object;
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
        #endregion

        #region Data Loading
        private async Task LoadUserData()
        {
            // Если _userId == -1 — свой профиль
            IsOwnProfile = _userId == -1 || (_userService.CurrentUser != null && _userService.CurrentUser.Id == _userId);
            RefreshCommands();
            if (IsOwnProfile)
            {
                if (_userService.CurrentUser == null)
                {
                    // Пользователь ещё не загружен – ждём или выходим
                    return;
                }
                User = _userService.CurrentUser;
            }
            else
            {
                var response = await _userService.LoadUserByIdAsync(_userId);
                if (response?.Object == null)
                {
                    await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось загрузить пользователя", "OK");
                    return;
                }
                User = response.Object;
            }

            // Заполняем отображаемые поля
            Phone = User.Phone ?? "";
            Email = User.Email ?? "";
            BirthDate = User.BirthDate?.ToString("dd.MM.yyyy") ?? "";
            About = User.About ?? "";

            string[]? fio = User.FullName?.Split(' ');
            if (fio != null)
            {
                Surname = fio.Length > 0 ? fio[0] : "";
                Name = fio.Length > 1 ? fio[1] : "";
                Patronymic = fio.Length > 2 ? fio[2] : "";
            }
        }

        public async Task LoadData()
        {
            if (_loading) return;
            _loading = true;

            try
            {
                await LoadUserData();
                await LoadUserTasksAsync(reset: true);
            }
            finally
            {
                _loading = false;
            }
        }

        private void OnUserServicePropertyChanged(object? sender, PropertyChangedEventArgs e)
        {
            // Если мы смотрим свой профиль и CurrentUser изменился – обновляем данные
            if (IsOwnProfile && e.PropertyName == nameof(IUserService.CurrentUser))
            {
                _ = LoadData(); // асинхронный вызов без ожидания
            }
        }

        private void RefreshCommands()
        {
            (LogoutCommand as Command)?.ChangeCanExecute();
            (GoToEditCommand as Command)?.ChangeCanExecute();
        }
        #endregion

        #region IDisposable
        public void Dispose()
        {
            _userService.PropertyChanged -= OnUserServicePropertyChanged;
        }
        #endregion
    }
}