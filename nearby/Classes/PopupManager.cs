using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using CommunityToolkit.Maui;
using CommunityToolkit.Maui.Extensions;
using nearby.ContentViews.Elements;

namespace nearby.Classes
{
    public static class PopupManager
    {
        public static PopupOptions options = new PopupOptions
        {
            PageOverlayColor = Colors.Transparent,
            CanBeDismissedByTappingOutsideOfPopup = true,
            Shape = null
        };

        public static INavigation navigation => Application.Current?.Windows[0]?.Page?.Navigation;

        public static PopupMenu Create(ObservableCollection<PopupItem> list, Thickness margin)
        {
            var popup = new PopupMenu(list);
            popup.HorizontalOptions = LayoutOptions.End;
            popup.VerticalOptions = LayoutOptions.Start;
            popup.Margin = margin;
            return popup;
        }

        public static async Task Show(PopupMenu popup)
        {
            if (navigation != null)
            {
                var result = await navigation.ShowPopupAsync(popup, options);
            }
        }
    }
}
