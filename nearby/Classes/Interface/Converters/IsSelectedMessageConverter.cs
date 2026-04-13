using System.Globalization;

namespace nearby.Classes.Interface.Converters
{
    public class IsSelectedMessageConverter : IValueConverter
    {
        public object Convert(object value, Type targetType, object parameter, CultureInfo culture)
        {
            // value = IsMenuVisible (bool)
            // parameter = текущее сообщение из DataTemplate
            if (value is bool isVisible && isVisible && parameter is Models.Message currentMessage)
            {
                // Получаем ViewModel из BindingContext страницы
                var viewModel = Application.Current.MainPage?.BindingContext as ViewModels.ChatDetailViewModel;
                return viewModel?.SelectedMessage == currentMessage;
            }
            return false;
        }

        public object ConvertBack(object value, Type targetType, object parameter, CultureInfo culture)
            => throw new NotImplementedException();
    }
}