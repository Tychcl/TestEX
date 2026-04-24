using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;

namespace nearby.ViewModels
{
    public class SettingsItem
    {
        public string Image { get; set; }
        public string Text { get; set; }
        public string Page { get; set; }
        public SettingsItem(string image, string text, string page)
        {
            Image = image;
            Text = text;
            Page = page;
        }
    }

    public partial class SettingsViewModel : BaseViewModel
    {
        [ObservableProperty]
        private ObservableCollection<SettingsItem> _settingsItems = new()
        {
            new("lightdark.svg", "Сменить цветовую тему", "no page yet :C ")
        };

        [RelayCommand]
        private async void ChangeThemeAsync()
        {

        }
    }
}
