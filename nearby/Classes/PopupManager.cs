using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using CommunityToolkit.Maui;
using CommunityToolkit.Maui.Extensions;
using Microsoft.Maui.Controls;
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

        public static PopupMenu Create(ObservableCollection<PopupItem> list, Thickness margin = default(Thickness), LayoutOptions horizontal = default(LayoutOptions), LayoutOptions vertical = default(LayoutOptions))
        {
            var popup = new PopupMenu(list);
            popup.HorizontalOptions = horizontal;
            popup.VerticalOptions = vertical;
            popup.Margin = margin;
            return popup;
        }

        public static async Task Show(PopupMenu popup, INavigation? nav = null)
        {
            INavigation n = nav ?? navigation;
            await n.ShowPopupAsync(popup, options);
        }

        public static async Task Show(PopupMenu popup, double x, double y, INavigation? nav = null)
        {
            double popupWidth = popup.Width / DeviceDisplay.Current.MainDisplayInfo.Density;
            double popupHeight = popup.Height / DeviceDisplay.Current.MainDisplayInfo.Density;
            double Width = x / DeviceDisplay.Current.MainDisplayInfo.Density;
            double Height = y / DeviceDisplay.Current.MainDisplayInfo.Density;
            Width = Width < popupWidth ? popupWidth : Width;
            Height = Height < popupHeight ? popupHeight : Height;
            //double width = Application.Current.Windows[0].Page.Width;
            //double height = Application.Current.Windows[0].Page.Height;
            //popup.AnchorX = x / width; 
            //popup.AnchorY = y / height;
            popup.Margin = new Thickness(Math.Max(0, Width), Math.Max(0, Height), 0, 0);
            await Show(popup, nav);
        }
    }
}
