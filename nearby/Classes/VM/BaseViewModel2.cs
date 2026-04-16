using CommunityToolkit.Mvvm.ComponentModel;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace nearby.Classes.VM
{
    public partial class BaseViewModel2 : ObservableValidator
    {
        private string? _errorMessage;
        public string? ErrorMessage
        {
            get => _errorMessage;
            private set => SetProperty(ref _errorMessage, value);
        }
        protected virtual void OnErrorsChanged(object? sender, DataErrorsChangedEventArgs e)
        {
            var allErrors = GetErrors()
                .SelectMany(err => err.ErrorMessage != null ? new[] { err.ErrorMessage } : Array.Empty<string>())
                .Distinct();
            ErrorMessage = string.Join(Environment.NewLine, allErrors);
        }

        protected virtual Task ShowErrorAsync(string message)
        {
            return Application.Current.MainPage.DisplayAlert("Ошибка", message, "OK");
        }
    }
}
