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
    public class BaseViewModel: NotifyPropertyChanged
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

        private bool _isBusy;
        public bool IsBusy
        {
            get => _isBusy;
            set => SetField(ref _isBusy, value);
        }



        protected async Task ExecuteAsync(Func<Task> action, params ICommand[] dependentCommands)
        {
            if (IsBusy) return;
            IsBusy = true;
            RefreshCommands(dependentCommands);
            try
            {
                await action();
            }
            catch (Exception ex)
            {
                await Application.Current.MainPage.DisplayAlert("Ошибка", ex.Message, "OK");
            }
            finally
            {
                IsBusy = false;
                RefreshCommands(dependentCommands);
            }
        }

        protected void RefreshCommands(params ICommand[] commands)
        {
            foreach (var cmd in commands)
            {
                (cmd as Command)?.ChangeCanExecute();
            }
        }

        public virtual void RefreshCommands()
        {

        }

        protected virtual Task ShowErrorAsync(string message)
        {
            return Application.Current.MainPage.DisplayAlert("Ошибка", message, "OK");
        }
    
    

    }
}
