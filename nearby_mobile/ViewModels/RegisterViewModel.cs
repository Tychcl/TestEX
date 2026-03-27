using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Input;
using nearby_mobile.Interfaces;
using nearby_mobile.Services;
using nearby_mobile.Classes;

namespace nearby_mobile.ViewModels;

public class RegisterViewModel : BaseViewModel, INotifyPropertyChanged
{
    private readonly IAuthService _authService;

    private string _fullName;
    private string _phone;
    private string _email;
    private string _password;
    private string _confirm;

    public RegisterViewModel(IAuthService authService)
    {
        _authService = authService;
        RegisterCommand = new Command(async () => await RegisterAsync());
    }

    public string FullName
    {
        get => _fullName;
        set
        {
            if (_fullName != value)
            {
                _fullName = value;
                SetField(ref _fullName, value);
            }
        }
    }

    public string Phone
    {
        get => _phone;
        set
        {
            if (_phone != value)
            {
                _phone = value;
                SetField(ref _phone, value);
            }
        }
    }

    public string Email
    {
        get => _email;
        set
        {
            if (_email != value)
            {
                _email = value;
                SetField(ref _email, value);
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
                SetField(ref _password, value);
            }
        }
    }

    public string Confirm
    {
        get => _confirm;
        set
        {
            if (_confirm != value)
            {
                _confirm = value;
                SetField(ref _confirm, value);
            }
        }
    }

    public ICommand RegisterCommand { get; }

    private async Task RegisterAsync()
    {
        if (string.IsNullOrWhiteSpace(FullName) ||
            (string.IsNullOrWhiteSpace(Phone) && string.IsNullOrWhiteSpace(Email)) ||
            string.IsNullOrWhiteSpace(Password) || string.IsNullOrWhiteSpace(Confirm))
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Заполните обязательные поля", "OK");
            return;
        }

        if (Password != Confirm)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Пароли не совпадают", "OK");
            return;
        }

        var success = await _authService.RegisterAsync(FullName, Phone, Email, Password);
        if (success)
        {
            await Application.Current.MainPage.DisplayAlert("Успех", "Регистрация прошла успешно. Теперь войдите.", "OK");
            await Application.Current.MainPage.Navigation.PopAsync();
        }
        else
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Регистрация не удалась. Возможно, пользователь уже существует.", "OK");
        }
    }
}