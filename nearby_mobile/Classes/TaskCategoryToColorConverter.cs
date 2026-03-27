using System.Globalization;
using nearby_mobile.ViewModels;

public class TaskCategoryToColorConverter : IValueConverter
{
    public object Convert(object value, Type targetType, object parameter, CultureInfo culture)
    {
        if (value is TaskCategory selected && parameter is TaskCategory category)
        {
            return selected == category
                ? (Color)Application.Current.Resources["Primary"]
                : (Color)Application.Current.Resources["Gray300"];
        }
        return Colors.Gray;
    }

    public object ConvertBack(object value, Type targetType, object parameter, CultureInfo culture)
        => throw new NotImplementedException();
}