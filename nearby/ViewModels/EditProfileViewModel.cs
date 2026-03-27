using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Input;
using Microsoft.Maui.ApplicationModel;
using Microsoft.Maui.Media;
using nearby.Classes;
using nearby.Interfaces;
using nearby.Models;
using nearby.Services;
using static Microsoft.Maui.ApplicationModel.Permissions;

namespace nearby.ViewModels;

public class EditProfileViewModel : BaseViewModel, INotifyPropertyChanged
{

    #region services
    private readonly IUserService _userService;
    #endregion

    #region variables
    private User _user;
    public User User
    {
        get => _user;
        set { SetField(ref _user, value); }
    }

    private string? _educationStartYear;
    public string? EducationStartYear
    {
        get => _educationStartYear;
        set => SetField(ref _educationStartYear, value);
    }

    private string? _educationEndYear;
    public string? EducationEndYear
    {
        get => _educationEndYear;
        set => SetField(ref _educationEndYear, value);
    }

    private string _currentPassword = string.Empty;
    public string CurrentPassword
    {
        get => _currentPassword;
        set => SetField(ref _currentPassword, value);
    }
    #endregion

    #region Icommands
    public ICommand SaveCommand { get; }
    public ICommand ChooseImageCommand { get; }
    #endregion

    public EditProfileViewModel(IUserService userService)
    {
        _userService = userService;
        PageTitle = "Редактирование профиля";
        User = _userService.CurrentUser.Copy();

        GoBackCommand = new Command(async () => await GoBackAsync());
        SaveCommand = new Command(async () => await SaveAsync());
    }

    #region commands
    private async Task SaveAsync()
    {
        if (string.IsNullOrWhiteSpace(CurrentPassword))
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Введите текущий пароль для подтверждения изменений", "OK");
            return;
        }

        if (string.IsNullOrWhiteSpace(User.FullName))
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "ФИО обязательно для заполнения", "OK");
            return;
        }

        var updatedData = new
        {
            full_name = User.FullName,
            city = User.City,
            birth_date = User.BirthDate?.ToString("yyyy-MM-dd"),
            email = User.Email,
            phone = User.Phone,
            about = User.About,
            // profile_picture = ... // если нужно отправить URL (после загрузки на сервер)
            education_institution = User.EducationInstitution,
            education_degree = User.EducationDegree,
            education_field = User.EducationField,
            education_start_year = string.IsNullOrWhiteSpace(EducationStartYear) ? 0 : int.Parse(EducationStartYear),
            education_end_year = string.IsNullOrWhiteSpace(EducationEndYear) ? 0 : int.Parse(EducationEndYear),
            current_password = CurrentPassword
        };

        var r = await _userService.UpdateUserByIdAsync(updatedData);

        if(r.result != true)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", r.message, "OK");
            return;
        }

        if ((bool)r.result)
        {
            await Application.Current.MainPage.DisplayAlert("Успех", "Данные сохранены", "OK");
            await Application.Current.MainPage.Navigation.PopModalAsync();
        }
    }
    #endregion

    #region func

    #endregion
}