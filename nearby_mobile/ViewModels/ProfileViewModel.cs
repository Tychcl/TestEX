using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Input;
using nearby_mobile.Interfaces;
using nearby_mobile.Services;
using nearby_mobile.Models;
using nearby_mobile.Views;

public class ProfileViewModel : INotifyPropertyChanged, IDisposable
{
    private readonly IUserService _userService;
    private readonly IAuthService _authService;
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

    public ProfileViewModel(
        IUserService userService,
        IAuthService authService,
        IServiceProvider serviceProvider)
    {
        _userService = userService;
        _authService = authService;
        _serviceProvider = serviceProvider;

        LogoutCommand = new Command(async () => await LogoutAsync(), () => IsOwnProfile);
        GoToEditCommand = new Command(async () => await GoToEditAsync(), () => IsOwnProfile);

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
        _isInitialized = true;
    }

    private void OnUserServicePropertyChanged(object? sender, PropertyChangedEventArgs e)
    {
        if (_isOwnProfile && e.PropertyName == nameof(IUserService.CurrentUser))
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
        Application.Current.MainPage = new NavigationPage(_serviceProvider.GetRequiredService<LoginPage>());
    }

    private async Task GoToEditAsync()
    {
        if (!IsOwnProfile) return;
        var editPage = _serviceProvider.GetRequiredService<EditProfilePage>();
        await Shell.Current.Navigation.PushModalAsync(editPage);
    }

    public event PropertyChangedEventHandler? PropertyChanged;
    protected virtual void OnPropertyChanged([CallerMemberName] string propertyName = null)
    {
        PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
    }

    protected bool SetField<T>(ref T field, T value, [CallerMemberName] string propertyName = null)
    {
        if (EqualityComparer<T>.Default.Equals(field, value)) return false;
        field = value;
        OnPropertyChanged(propertyName);
        return true;
    }

    public void Dispose()
    {
        _userService.PropertyChanged -= OnUserServicePropertyChanged;
    }
}