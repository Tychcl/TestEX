using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Input;
using nearby_mobile.Interfaces;
using nearby_mobile.Services;
using nearby_mobile.Views;

namespace nearby_mobile.ViewModels;

public class ProfileViewModel : INotifyPropertyChanged, IDisposable
{
    private readonly IUserService _userService;
    private readonly IAuthService _authService;
    private readonly IServiceProvider _serviceProvider;

    public event PropertyChangedEventHandler? PropertyChanged;

    public ProfileViewModel(
        IUserService userService,
        IAuthService authService,
        IServiceProvider serviceProvider)
    {
        _userService = userService;
        _authService = authService;
        _serviceProvider = serviceProvider;

        LogoutCommand = new Command(async () => await LogoutAsync());

        _userService.PropertyChanged += OnUserServicePropertyChanged;

        UpdateFromUser();
    }

    private void OnUserServicePropertyChanged(object? sender, PropertyChangedEventArgs e)
    {
        if (e.PropertyName == nameof(IUserService.CurrentUser))
        {
            UpdateFromUser();
        }
    }

    private void UpdateFromUser()
    {
        var user = _userService.CurrentUser;
        if (user != null)
        {
            FullName = user.FullName;
            Phone = user.Phone ?? "";
            Email = user.Email ?? "";
            BirthDate = user.BirthDate?.ToString("dd.MM.yyyy") ?? "";
            About = user.About ?? "";
        }
    }

    public string Name;
    public string Familia;
    public string Otchestvo;

    private string _fullName;
    public string FullName
    {
        get => _fullName;
        set { 
            if (_fullName != value) 
            { 
                _fullName = value;
                if (!string.IsNullOrEmpty(value))
                {
                    string[] m = value.ToString().Split(' ');
                    Name = m[1];
                    Familia = m[0];
                    Otchestvo = m[2];
                }
                OnPropertyChanged(); 
            } 
        }
    }

    private string _phone;
    public string Phone
    {
        get => _phone;
        set { if (_phone != value) { _phone = value; OnPropertyChanged(); } }
    }

    private string _email;
    public string Email
    {
        get => _email;
        set { if (_email != value) { _email = value; OnPropertyChanged(); } }
    }

    private string _birthDate;
    public string BirthDate
    {
        get => _birthDate;
        set { if (_birthDate != value) { _birthDate = value; OnPropertyChanged(); } }
    }

    private string _about;
    public string About
    {
        get => _about;
        set { if (_about != value) { _about = value; OnPropertyChanged(); } }
    }

    private string _education = "Пермский авиационный техникум имени А.Д. Швецова\n2022 - 2026\nСреднее специальное";
    public string Education
    {
        get => _education;
        set { if (_education != value) { _education = value; OnPropertyChanged(); } }
    }

    public ICommand LogoutCommand { get; }

    private async Task LogoutAsync()
    {
        await _authService.LogoutAsync();
        Application.Current.MainPage = new NavigationPage(_serviceProvider.GetRequiredService<LoginPage>());
    }

    protected void OnPropertyChanged([CallerMemberName] string prop = "")
    {
        PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(prop));
    }

    public void Dispose()
    {
        _userService.PropertyChanged -= OnUserServicePropertyChanged;
    }
}