using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using System.ComponentModel.DataAnnotations;
using nearby.Interfaces;
using nearby.Services;
using nearby.Views.Main;
using nearby.Classes.Validation;

public partial class LoginViewModel : ObservableValidator
{
    private readonly IAuthService _authService;
    private readonly IUserService _userService;
    private readonly IServiceProvider _serviceProvider;

    [ObservableProperty]
    [NotifyDataErrorInfo]
    [Required(ErrorMessage = "Поле обязательно для заполнения")]
    [ValidateWithValidator(validatorName: nameof(Validate.EmailOrPhoneValidator), ErrorMessage = "Неверный логин")]
    private string _login;

    [ObservableProperty]
    [NotifyDataErrorInfo]
    [Required(ErrorMessage = "Пароль не может быть пустым")]
    private string _password;

    public string ErrorMessage => string.Join(Environment.NewLine, GetErrors().Select(e => e.ErrorMessage));
    public bool HasErrors => HasErrors;

    private bool CanLogin() => !HasErrors;
    [RelayCommand(CanExecute = nameof(CanLogin))]
    private async Task LoginAsync()
    {
        ValidateAllProperties();
        if (HasErrors) return;

        var success = await _authService.LoginAsync(Login, Password);
        if (success.result is not true)
            throw new Exception(success.message ?? "Ошибка входа");

        _userService.CurrentUser = success.Data;
        Application.Current.MainPage = _serviceProvider.GetRequiredService<MainShell>();
    }
}