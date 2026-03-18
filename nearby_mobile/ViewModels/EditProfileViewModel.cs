using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Input;
using Microsoft.Maui.ApplicationModel;
using Microsoft.Maui.Media;
using nearby_mobile.Interfaces;
using nearby_mobile.Models;
using nearby_mobile.Services;

namespace nearby_mobile.ViewModels;

public class EditProfileViewModel : INotifyPropertyChanged, IQueryAttributable
{
    private readonly IUserService _userService;
    private readonly IAuthService _authService;
    private User? _originalUser;

    public event PropertyChangedEventHandler? PropertyChanged;

    // Свойства для привязки
    private string _fullName = string.Empty;
    public string FullName
    {
        get => _fullName;
        set { if (_fullName != value) { _fullName = value; OnPropertyChanged(); } }
    }

    private string? _city;
    public string? City
    {
        get => _city;
        set { if (_city != value) { _city = value; OnPropertyChanged(); } }
    }

    private DateTime? _birthDate;
    public DateTime? BirthDate
    {
        get => _birthDate;
        set { if (_birthDate != value) { _birthDate = value; OnPropertyChanged(); } }
    }

    private string? _email;
    public string? Email
    {
        get => _email;
        set { if (_email != value) { _email = value; OnPropertyChanged(); } }
    }

    private string? _phone;
    public string? Phone
    {
        get => _phone;
        set { if (_phone != value) { _phone = value; OnPropertyChanged(); } }
    }

    private string? _about;
    public string? About
    {
        get => _about;
        set { if (_about != value) { _about = value; OnPropertyChanged(); } }
    }

    private string? _profilePictureSource;
    public string? ProfilePictureSource
    {
        get => _profilePictureSource;
        set { if (_profilePictureSource != value) { _profilePictureSource = value; OnPropertyChanged(); } }
    }

    // Образование (одна запись)
    private string? _educationInstitution;
    public string? EducationInstitution
    {
        get => _educationInstitution;
        set { if (_educationInstitution != value) { _educationInstitution = value; OnPropertyChanged(); } }
    }

    private string? _educationDegree;
    public string? EducationDegree
    {
        get => _educationDegree;
        set { if (_educationDegree != value) { _educationDegree = value; OnPropertyChanged(); } }
    }

    private string? _educationField;
    public string? EducationField
    {
        get => _educationField;
        set { if (_educationField != value) { _educationField = value; OnPropertyChanged(); } }
    }

    private string? _educationStartYear;
    public string? EducationStartYear
    {
        get => _educationStartYear;
        set { if (_educationStartYear != value) { _educationStartYear = value; OnPropertyChanged(); } }
    }

    private string? _educationEndYear;
    public string? EducationEndYear
    {
        get => _educationEndYear;
        set { if (_educationEndYear != value) { _educationEndYear = value; OnPropertyChanged(); } }
    }

    private string _currentPassword = string.Empty;
    public string CurrentPassword
    {
        get => _currentPassword;
        set { if (_currentPassword != value) { _currentPassword = value; OnPropertyChanged(); } }
    }

    public DateTime Today => DateTime.Today;

    public ICommand PickImageCommand { get; }
    public ICommand SaveCommand { get; }
    public ICommand GoBackCommand { get; }

    public EditProfileViewModel(IUserService userService, IAuthService authService)
    {
        _userService = userService;
        _authService = authService;

        PickImageCommand = new Command(async () => await PickImageAsync());
        SaveCommand = new Command(async () => await SaveAsync());
        GoBackCommand = new Command(async () => await GoBackAsync());
    }

    private async Task GoBackAsync()
    {
        if (Shell.Current != null)
            await Shell.Current.Navigation.PopAsync();
        else
            await Application.Current.MainPage.Navigation.PopAsync();
    }

    public void ApplyQueryAttributes(IDictionary<string, object> query)
    {
        // Получаем текущего пользователя из сервиса (уже должен быть загружен)
        var user = _userService.CurrentUser;
        if (user != null)
        {
            _originalUser = user;
            FullName = user.FullName;
            City = user.City;
            BirthDate = user.BirthDate;
            Email = user.Email;
            Phone = user.Phone;
            About = user.About;
            ProfilePictureSource = user.ProfilePicture;
            EducationInstitution = user.EducationInstitution;
            EducationDegree = user.EducationDegree;
            EducationField = user.EducationField;
            EducationStartYear = user.EducationStartYear?.ToString();
            EducationEndYear = user.EducationEndYear?.ToString();
        }
    }

    private async Task PickImageAsync()
    {
        try
        {
            var result = await MediaPicker.PickPhotoAsync(new MediaPickerOptions
            {
                Title = "Выберите фото профиля"
            });

            if (result != null)
            {
                // Сохраняем локально (в app data directory) для предварительного просмотра
                var localFileName = $"profile_{DateTime.Now.Ticks}.jpg";
                var localPath = Path.Combine(FileSystem.AppDataDirectory, localFileName);
                using var stream = await result.OpenReadAsync();
                using var fileStream = File.OpenWrite(localPath);
                await stream.CopyToAsync(fileStream);

                // Устанавливаем локальный путь для отображения
                ProfilePictureSource = localPath;

                // В реальном приложении здесь нужно загрузить файл на сервер
                // и после успешной загрузки получить URL и обновить ProfilePictureSource.
                // Для примера пока просто сохраняем локально.
            }
        }
        catch (Exception ex)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", ex.Message, "OK");
        }
    }

    private async Task SaveAsync()
    {
        // Проверка наличия текущего пароля
        if (string.IsNullOrWhiteSpace(CurrentPassword))
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Введите текущий пароль для подтверждения изменений", "OK");
            return;
        }

        // Валидация обязательных полей
        if (string.IsNullOrWhiteSpace(FullName))
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "ФИО обязательно для заполнения", "OK");
            return;
        }

        // Формируем объект для отправки на сервер
        var updatedData = new
        {
            full_name = FullName,
            city = City,
            birth_date = BirthDate?.ToString("yyyy-MM-dd"),
            email = Email,
            phone = Phone,
            about = About,
            // profile_picture = ... // если нужно отправить URL (после загрузки на сервер)
            education_institution = EducationInstitution,
            education_degree = EducationDegree,
            education_field = EducationField,
            education_start_year = string.IsNullOrWhiteSpace(EducationStartYear) ? 0 : int.Parse(EducationStartYear),
            education_end_year = string.IsNullOrWhiteSpace(EducationEndYear) ? 0 : int.Parse(EducationEndYear),
            current_password = CurrentPassword
        };

        // Вызов сервиса для обновления
        var success = await _userService.UpdateUserAsync(updatedData);
        if (success)
        {
            await Application.Current.MainPage.DisplayAlert("Успех", "Данные сохранены", "OK");
            // Безопасная навигация назад
            if (Shell.Current != null)
                await Shell.Current.GoToAsync("..");
            else
                await Application.Current.MainPage.Navigation.PopAsync();
        }
        else
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось сохранить", "OK");
        }
    }

    protected virtual void OnPropertyChanged([CallerMemberName] string propertyName = "")
    {
        PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
    }
}