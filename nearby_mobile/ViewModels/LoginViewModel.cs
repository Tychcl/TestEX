using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Input;
using nearby_mobile.Interfaces;
using nearby_mobile.Services;
using nearby_mobile.Views;

namespace nearby_mobile.ViewModels;

public class LoginViewModel : INotifyPropertyChanged
{
    private readonly IAuthService _authService;
    private readonly IUserService _userService;
    private readonly IServiceProvider _serviceProvider;

    private string _login;
    private string _password;

    public event PropertyChangedEventHandler? PropertyChanged;

    public LoginViewModel(IAuthService authService, IUserService userService, IServiceProvider serviceProvider)
    {
        _authService = authService;
        _userService = userService;
        _serviceProvider = serviceProvider;

        LoginCommand = new Command(async () => await LoginAsync());
        GoToRegisterCommand = new Command(async () => await GoToRegisterAsync());
    }

    public string Login
    {
        get => _login;
        set
        {
            if (_login != value)
            {
                _login = value;
                OnPropertyChanged();
            }
        }
    }

    public string Password
    {
        get => _password;
        set
        {
            if (_password != value)
            {
                _password = value;
                OnPropertyChanged();
            }
        }
    }

    public ICommand LoginCommand { get; }
    public ICommand GoToRegisterCommand { get; }

    private async Task LoginAsync()
    {
        if (string.IsNullOrWhiteSpace(Login) || string.IsNullOrWhiteSpace(Password))
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Заполните все поля", "OK");
            return;
        }

        var success = await _authService.LoginAsync(Login, Password);
        if (success)
        {
            await _userService.LoadUserByIdAsync();
            Application.Current.MainPage = _serviceProvider.GetRequiredService<AppShell>();
        }
        else
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Неверный логин или пароль", "OK");
        }
    }

    private async Task GoToRegisterAsync()
    {
        var registerPage = _serviceProvider.GetRequiredService<RegisterPage>();
        await Application.Current.MainPage.Navigation.PushAsync(registerPage);
    }

    protected void OnPropertyChanged([CallerMemberName] string prop = "")
    {
        PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(prop));
    }
}