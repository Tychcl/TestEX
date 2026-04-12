using System.ComponentModel;
using System.Windows.Input;
using nearby.Interfaces;
using nearby.Services;
using nearby.Views.Main;
using nearby.Classes;

namespace nearby.ViewModels
{
    public class LoginViewModel : BaseViewModel
    {
        private readonly IAuthService _authService;
        private readonly IUserService _userService;
        private readonly IServiceProvider _serviceProvider;

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

        public ICommand LoginCommand { get; }

        public LoginViewModel(IAuthService authService, IUserService userService, IServiceProvider serviceProvider)
        {
            _authService = authService;
            _userService = userService;
            _serviceProvider = serviceProvider;

            LoginCommand = new Command(async () => await ExecuteAsync(LoginAsync, LoginCommand));
        }

        private async Task LoginAsync()
        {
            if (string.IsNullOrWhiteSpace(Login) || string.IsNullOrWhiteSpace(Password))
                throw new Exception("Заполните все поля");

            var success = await _authService.LoginAsync(Login, Password);
            if (success.result is not true)
                throw new Exception(success.message ?? "Ошибка входа");

            _userService.CurrentUser = success.Data;
            Application.Current.MainPage = _serviceProvider.GetRequiredService<MainShell>();
        }
    }
}