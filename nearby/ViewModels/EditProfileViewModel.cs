using System.ComponentModel;
using System.Windows.Input;
using nearby.Classes;
using nearby.Models;
using nearby.Services;

namespace nearby.ViewModels
{
    public class EditProfileViewModel : BaseViewModel, INotifyPropertyChanged
    {
        private readonly IUserService _userService;

        private User _user;
        public User User
        {
            get => _user;
            set => SetField(ref _user, value);
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

        public ICommand SaveCommand { get; }

        public EditProfileViewModel(IUserService userService)
        {
            _userService = userService;
            PageTitle = "Редактирование профиля";
            User = _userService.CurrentUser.Copy();

            GoBackCommand = new Command(async () => await ExecuteAsync(GoBackAsync, GoBackCommand));
            SaveCommand = new Command(async () => await ExecuteAsync(SaveAsync, SaveCommand));
        }

        private async Task SaveAsync()
        {
            if (string.IsNullOrWhiteSpace(CurrentPassword))
                throw new Exception("Введите текущий пароль для подтверждения изменений");

            if (string.IsNullOrWhiteSpace(User.FullName))
                throw new Exception("ФИО обязательно для заполнения");

            var updatedData = new
            {
                full_name = User.FullName,
                city = User.City,
                birth_date = User.BirthDate?.ToString("yyyy-MM-dd"),
                email = User.Email,
                phone = User.Phone,
                about = User.About,
                education_institution = User.EducationInstitution,
                education_degree = User.EducationDegree,
                education_field = User.EducationField,
                education_start_year = string.IsNullOrWhiteSpace(EducationStartYear) ? 0 : int.Parse(EducationStartYear),
                education_end_year = string.IsNullOrWhiteSpace(EducationEndYear) ? 0 : int.Parse(EducationEndYear),
                current_password = CurrentPassword
            };

            var r = await _userService.UpdateUserByIdAsync(updatedData);
            if (r.result != true)
                throw new Exception(r.message);

            await Application.Current.MainPage.DisplayAlert("Успех", "Данные сохранены", "OK");
            await Application.Current.MainPage.Navigation.PopModalAsync();
        }
    }
}