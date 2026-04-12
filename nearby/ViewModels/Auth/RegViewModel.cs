using System.Windows.Input;
using nearby.Interfaces;
using nearby.Classes;

namespace nearby.ViewModels
{
    public class RegViewModel : BaseViewModel
    {
        private readonly IAuthService _authService;

        private string _fullName;
        public string FullName
        {
            get => _fullName;
            set => SetField(ref _fullName, value);
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

        private string _password;
        public string Password
        {
            get => _password;
            set => SetField(ref _password, value);
        }

        private string _confirm;
        public string Confirm
        {
            get => _confirm;
            set => SetField(ref _confirm, value);
        }

        public ICommand RegisterCommand { get; }

        public RegViewModel(IAuthService authService)
        {
            _authService = authService;
            RegisterCommand = new Command(async () => await ExecuteAsync(RegisterAsync, RegisterCommand));
        }

        private async Task RegisterAsync()
        {
            if (string.IsNullOrWhiteSpace(FullName) ||
                (string.IsNullOrWhiteSpace(Phone) && string.IsNullOrWhiteSpace(Email)) ||
                string.IsNullOrWhiteSpace(Password) || string.IsNullOrWhiteSpace(Confirm))
                throw new Exception("Заполните обязательные поля");

            if (Password != Confirm)
                throw new Exception("Пароли не совпадают");

            var success = await _authService.RegisterAsync(FullName, Phone, Email, Password);
            if (success.result is not true)
                throw new Exception("Регистрация не удалась. Возможно, пользователь уже существует.");

            await Application.Current.MainPage.DisplayAlert("Успех", "Регистрация прошла успешно. Теперь войдите.", "OK");
            await Application.Current.MainPage.Navigation.PopAsync();
        }
    }
}