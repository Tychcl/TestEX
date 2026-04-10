using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Linq;
using System.Runtime.CompilerServices;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Input;

namespace nearby.Classes
{
    public class BaseViewModel
    {
        public ICommand GoBackCommand { get; set; }
        public static async Task GoBackAsync()
        {
            await Shell.Current.GoToAsync("..");
        }

        private string? _pagetitle;
        public string? PageTitle
        {
            get => _pagetitle;
            set => SetField(ref _pagetitle, value);
        }

        public event PropertyChangedEventHandler? PropertyChanged;
        protected virtual void OnPropertyChanged([CallerMemberName] string propertyName = null)
        {
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
        }

        protected bool SetField<T>(ref T field, T value, [CallerMemberName] string propertyName = null)
        {
            if (EqualityComparer<T>.Default.Equals(field, value)) return false;
            field = value;
            OnPropertyChanged(propertyName);
            return true;
        }
    }
}
