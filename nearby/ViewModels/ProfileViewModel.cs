using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Diagnostics;
using System.Runtime.CompilerServices;
using System.Threading.Tasks;
using System.Windows.Input;
using nearby.Classes;
using nearby.Interfaces;
using nearby.Models;
using nearby.Services;
using nearby.Views.Auth;
using nearby.Views.Additional;
using static Microsoft.Maui.ApplicationModel.Permissions;

namespace nearby.ViewModels
{
    public enum TaskCategory
    {
        Created,      // задачи, созданные пользователем
        InProgress,   // задачи, которые пользователь взял в работу
        Completed     // выполненные задачи
    }
    public class ProfileViewModel : BaseViewModel, INotifyPropertyChanged, IDisposable
    {
        #region services
        private readonly IUserService _userService;
        private readonly IAuthService _authService;
        private readonly ITaskService _taskService;
        private readonly IServiceProvider _serviceProvider;
        #endregion

        #region variables
        private const int TaskPageSize = 10;
        private int _currentTaskPage = 1;
        private bool _hasMoreTasks = true;
        private bool _isLoadingTasks;

        private int _id;

        private TaskCategory _selectedCategory;
        public TaskCategory SelectedCategory
        {
            get => _selectedCategory;
            set => SetField(ref _selectedCategory, value);
        }

        private ObservableCollection<TaskItem> _userTasks = new();
        public ObservableCollection<TaskItem> UserTasks
        {
            get => _userTasks;
            set 
            { 
                if (SetField(ref _userTasks, value))
                {
                    _currentTaskPage = 1;
                    _hasMoreTasks = true;
                    LoadUserTasksCommand.Execute(null);
                }
            }
        }

        private User _user;
        public User User
        {
            get => _user;
            set { SetField(ref _user, value); }
        }

        private string _surname; //фамилия
        public string Surname
        {
            get => _surname;
            set { SetField(ref _surname, value); }
        }

        private string _name; //имя
        public string Name
        {
            get => _name;
            set { SetField(ref _name, value); }
        }

        private string _patronymic; //отчество
        public string Patronymic
        {
            get => _patronymic;
            set { SetField(ref _patronymic, value); }
        }

        private string _phone; 
        public string Phone
        {
            get => _phone;
            set { SetField(ref _phone, value); }
        }

        private string _email;
        public string Email
        {
            get => _email;
            set {  SetField(ref _email, value); }
        }

        private string _birthdate;
        public string BirthDate
        {
            get => _birthdate;
            set { SetField(ref _birthdate, value); }
        }

        private string _about;
        public string About
        {
            get => _about;
            set { SetField(ref _about, value); }
        }

        private bool _isownprofile;
        public bool IsOwnProfile
        {
            get => _isownprofile;
            set { SetField(ref _isownprofile, value); }
        }
        #endregion

        #region Icommands
        public ICommand LogoutCommand { get; }
        public ICommand GoToEditCommand { get; }
        public ICommand SelectCategoryCommand { get; }
        public ICommand LoadUserTasksCommand { get; }
        #endregion

        public ProfileViewModel(
            IUserService userService,
            IAuthService authService,
            ITaskService taskService,
            IServiceProvider serviceProvider,
            int id = -1)
        {
            _userService = userService;
            _authService = authService;
            _taskService = taskService;
            _serviceProvider = serviceProvider;
            _userService.PropertyChanged += UpdateUserData;

            _id = id;
            LoadUserData();
            SelectedCategory = TaskCategory.Created;

            LogoutCommand = new Command(async () => await LogoutAsync(), () => IsOwnProfile);
            GoToEditCommand = new Command(async () => await GoToEditAsync(), () => IsOwnProfile);
            SelectCategoryCommand = new Command<TaskCategory>(category => SelectedCategory = category);
            LoadUserTasksCommand = new Command(async () => await LoadUserTasksAsync(reset: true));
        }

        #region commands
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
            var page = _serviceProvider.GetRequiredService<EditProfilePage>();
            var vm = _serviceProvider.GetRequiredService<EditProfileViewModel>();
            page.BindingContext = vm;
            await Application.Current.MainPage.Navigation.PushModalAsync(page);
        }

        public async Task LoadUserTasksAsync(bool reset)
        {
            if (_isLoadingTasks || _user == null) return;

            if (reset)
            {
                _currentTaskPage = 1;
                _hasMoreTasks = true;
                UserTasks.Clear();
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

                if (tasks.result is null || tasks.Object is null) return;

                if (tasks.Object.Any())
                {
                    foreach (var task in tasks.Object) UserTasks.Add(task);
                    _currentTaskPage++;
                    if (tasks.Object.Count < TaskPageSize)
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

        #region func
        private void UpdateUserData(object? sender, PropertyChangedEventArgs e)
        {
            if (e.PropertyName == nameof(IUserService.CurrentUser))
            {
                LoadUserData();
            }
        }

        private void LoadUserData()
        {
            if(_userService.CurrentUser is null)
            {
                return;
            }

            User = _userService.CurrentUser;
            IsOwnProfile = User.Id == _id | _id == -1;

            Phone = User.Phone ?? "";
            Email = User.Email ?? "";
            BirthDate = User.BirthDate?.ToString("dd.MM.yyyy") ?? "";
            About = User.About ?? "";

            string[]? fio = User.FullName?.Split(' ');
            if(fio is not null)
            {
                Surname = fio.Length > 0 ? fio[0] : "";
                Name = fio.Length > 1 ? fio[1] : "";
                Patronymic = fio.Length > 2 ? fio[2] : "";
            }
        }

        public void Dispose()
        {
            _userService.PropertyChanged -= UpdateUserData;
        }
        #endregion


    }
}