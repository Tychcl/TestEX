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
            set => SetField(ref _login, value);
        }

        private string _password;
        public string Password
        {
            get => _password;
            set => SetField(ref _password, value);
        }

        private bool _isloginvalid;
        public bool IsLoginValid
        {
            get => _isloginvalid;
            set { if (SetField(ref _isloginvalid, value)) HasErrors = !IsLoginValid || !IsPasswordValid; }
        }

        private bool _ispasswordvalid;
        public bool IsPasswordValid
        {
            get => _ispasswordvalid;
            set { if (SetField(ref _ispasswordvalid, value)) HasErrors = !IsLoginValid || !IsPasswordValid; }
        }

        private string? _errormsg = "Необходимо заполнить поля";
        public string? ErrorMsg
        {
            get => _errormsg;
            set => SetField(ref _errormsg, value);
        }

        private bool _he;
        public bool HasErrors
        {
            get => _he;
            set 
            { 
                if (SetField(ref _he, value))
                {
                    RefreshCommands();
                }
                ErrorMsg = IsLoginValid ? "Логин должен быть в формате +79999999999 или example@mail.com"
                        : !IsPasswordValid ? "" :
                        "Пароль должен содержать:\nТолько латинские буквы\nХотя бы одну заглавную букву\nХотя бы одну строчную букву\nХотя бы одну цифру\nХотя бы один специальный символ #?!@$%^&*-._\nБыть длиной не менее 8 символов";
            }
        }

        public ICommand LoginCommand { get; }

        public LoginViewModel(IAuthService authService, IUserService userService, IServiceProvider serviceProvider)
        {
            _authService = authService;
            _userService = userService;
            _serviceProvider = serviceProvider;

            HasErrors = true;
            LoginCommand = new Command(async () => await ExecuteAsync(LoginAsync, LoginCommand), () => !HasErrors);
        }

        public override void RefreshCommands()
        {
            (LoginCommand as Command)?.ChangeCanExecute();
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