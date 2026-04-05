using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Input;
using nearby.Interfaces;
using nearby.Services;
using nearby.Views.Main;
using nearby.Classes;
using nearby.Models;

namespace nearby.ViewModels;

public class LoginViewModel : BaseViewModel ,INotifyPropertyChanged
{
    #region services
    private readonly IAuthService _authService;
    private readonly IUserService _userService;
    private readonly IServiceProvider _serviceProvider;
    #endregion

    #region variables
    private string _login;
    public string Login
    {
        get => _login;
        set => SetField(ref _login, value);
    }

    private string _password;
    public string Password
    {
        get => _password;
        set => SetField(ref _password, value);
    }
    #endregion

    #region ICommands
    public ICommand LoginCommand { get; }
    #endregion

    public LoginViewModel(IAuthService authService, IUserService userService, IServiceProvider serviceProvider)
    {
        _authService = authService;
        _userService = userService;
        _serviceProvider = serviceProvider;

        LoginCommand = new Command(async () => await LoginAsync());
    }

    #region commands
    private async Task LoginAsync()
    {
        if (string.IsNullOrWhiteSpace(Login) || string.IsNullOrWhiteSpace(Password))
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Заполните все поля", "OK");
            return;
        }

        ApiResponse<User> success = await _authService.LoginAsync(Login, Password);

        if (success.result is null || !(bool)success.result)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", success.message, "OK");
            return;
        }

        if ((bool)success.result)
        {
            //await _userService.LoadUserByIdAsync();
            _userService.CurrentUser = success.Object;
            Application.Current.MainPage = _serviceProvider.GetRequiredService<MainShell>();
        }
    }
    #endregion
}