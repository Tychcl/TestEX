using System.Collections.ObjectModel;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using nearby.Views.Additional.Settings;

namespace nearby.ViewModels
{
    public class SettingsItem
    {
        public string Icon { get; set; }
        public string Text { get; set; }
        public string Page { get; set; }
    }

    public partial class SettingsViewModel : BaseViewModel
    {
        [ObservableProperty]
        private ObservableCollection<SettingsItem> settingsItems;

        public SettingsViewModel()
        {
            settingsItems = new ObservableCollection<SettingsItem>
            {
                new SettingsItem
                {
                    Icon = (string)Application.Current.Resources["Theme"],
                    Text = "Смена темы",
                    Page = nameof(ThemeChangePage)
                },
                new SettingsItem
                {
                    Icon = (string)Application.Current.Resources["Language"],
                    Text = "Смена Языка",
                    Page = nameof(ThemeChangePage)
                }
            };
        }

        [RelayCommand]
        private async Task GoToPageAsync(string page)
        {
            if (string.IsNullOrEmpty(page))
                return;

            await Shell.Current.GoToAsync(page);
        }
    }
}