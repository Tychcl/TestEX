using System.Globalization;

namespace nearby.Classes.Interface.Converters;

public class MessageBackgroundConverter : IValueConverter
{
    public object Convert(object value, Type targetType, object parameter, CultureInfo culture)
    {
        if (value is int senderId && parameter is int currentUserId)
        {
            // Свои сообщения - зелёные, чужие - белые
            return senderId == currentUserId ? Color.FromArgb("#DCF8C6") : Colors.White;
        }
        return Colors.White;
    }

    public object ConvertBack(object value, Type targetType, object parameter, CultureInfo culture)
        => throw new NotImplementedException();
}