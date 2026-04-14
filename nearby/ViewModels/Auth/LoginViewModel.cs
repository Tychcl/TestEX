using System.ComponentModel;
using System.Windows.Input;
using nearby.Classes;
using nearby.Interfaces;
using nearby.Services;
using nearby.Views.Main;

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
            set
            {
                if (SetField(ref _login, value) && !_isLoginValid && !_errorMsgVisible)
                {
                    ErrorMsgVisible = !IsLoginValid && !string.IsNullOrWhiteSpace(Login);
                    ErrorMsg = !IsLoginValid ? "Логин должен быть в формате +79999999999 или example@mail.com" : "";
                }
            }
        }

        private string _password;
        public string Password
        {
            get => _password;
            set
            {
                if (SetField(ref _password, value))
                {
                    RefreshCommands();
                }
            }
        }

        private bool _isLoginValid;
        public bool IsLoginValid
        {
            get => _isLoginValid;
            set
            {
                if (SetField(ref _isLoginValid, value))
                {
                    RefreshCommands();
                    ErrorMsgVisible = !IsLoginValid && !string.IsNullOrWhiteSpace(Login);
                    ErrorMsg = !IsLoginValid ? "Логин должен быть в формате +79999999999 или example@mail.com" : "";
                }
            }
        }

        private bool _isPasswordValid;
        public bool IsPasswordValid
        {
            get => _isPasswordValid;
            set
            {
                if (SetField(ref _isPasswordValid, value))
                {
                    RefreshCommands();
                }
            }
        }

        private string? _errorMsg;
        public string? ErrorMsg
        {
            get => _errorMsg;
            set => SetField(ref _errorMsg, value);
        }

        private bool _errorMsgVisible;
        public bool ErrorMsgVisible
        {
            get => _errorMsgVisible;
            set => SetField(ref _errorMsgVisible, value);
        }

        public ICommand LoginCommand { get; }

        public LoginViewModel(IAuthService authService, IUserService userService, IServiceProvider serviceProvider)
        {
            _authService = authService;
            _userService = userService;
            _serviceProvider = serviceProvider;

            IsLoginValid = false;
            IsPasswordValid = false;

            LoginCommand = new Command(
                execute: async () => await ExecuteAsync(LoginAsync, LoginCommand),
                canExecute: () => IsLoginValid && IsPasswordValid
            );
        }

        public override void RefreshCommands()
        {
            (LoginCommand as Command)?.ChangeCanExecute();
        }

        private async Task LoginAsync()
        {
            ErrorMsgVisible = false;
            if (string.IsNullOrWhiteSpace(Login) || string.IsNullOrWhiteSpace(Password))
            {
                ErrorMsgVisible = true;
                ErrorMsg = "Заполните все поля";
                return;
            }

            var success = await _authService.LoginAsync(Login, Password);
            if (success.result is not true)
                throw new Exception(success.message ?? "Ошибка входа");

            _userService.CurrentUser = success.Data;
            Application.Current.MainPage = _serviceProvider.GetRequiredService<MainShell>();
        }
    }
}