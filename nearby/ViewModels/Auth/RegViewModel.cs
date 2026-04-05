using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Input;
using nearby.Interfaces;
using nearby.Services;
using nearby.Classes;

namespace nearby.ViewModels;

public class RegViewModel : BaseViewModel
{
    #region services
    private readonly IAuthService _authService;
    #endregion

    #region variables
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
    #endregion

    #region ICommands
    public ICommand RegisterCommand { get; }
    #endregion
    public RegViewModel(IAuthService authService)
    {
        _authService = authService;
        RegisterCommand = new Command(async () => await RegisterAsync());
    }

    #region commands
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
        if(success.result is not true)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Регистрация не удалась. Возможно, пользователь уже существует.", "OK");
        }
        await Application.Current.MainPage.DisplayAlert("Успех", "Регистрация прошла успешно. Теперь войдите.", "OK");
        await Application.Current.MainPage.Navigation.PopAsync();
    }
    #endregion
}