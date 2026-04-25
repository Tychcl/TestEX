using System;
using System.Collections.Generic;
using System.Linq;
using System.Reflection.Metadata;
using System.Text;
using System.Threading.Tasks;
using Microsoft.Maui.Controls;
using nearby.Resources.Themes;

namespace nearby.Classes
{
    public class ThemeDescription
    {
        public string Name;
        public string Mode;
        public string Color;
        public ThemeDescription(string name = "Такой", bool light = false, string color = "Нет")
        {
            Name = name;
            Mode = light ? "Светлая" : "Темная";
            Color = color;
        }
    }
    
    public static class ThemeManager
    {
        public const string LightBlue = "LightBlue";
        public const string LightPurple = "LightPurple";
        public const string DarkOrange = "DarkOrange";
        public const string DarkGreen = "DarkGreen";

        public static ResourceDictionary GetTheme(string name)
        {
            return name switch
            {
                LightBlue => new LightBlueTheme(),
                LightPurple => new LightPurpleTheme(),
                DarkOrange => new DarkOrangeTheme(),
                DarkGreen => new DarkGreenTheme(),
                _ => new LightBlueTheme()
            };
        }

        public static void ApplyTheme(string name)
        {
            var merged = Application.Current.Resources.MergedDictionaries;
            var oldThemes = merged.Where(md => md is ResourceDictionary rd && rd.Source?.OriginalString?.Contains("Theme") == true).ToList();
            foreach (var old in oldThemes)
                merged.Remove(old);
            merged.Add(GetTheme(name));
            Preferences.Set("user_theme", name);
        }

        public static ThemeDescription GetDescription(string name)
        {
            return name switch
            {
                LightBlue => new ThemeDescription("Холодное море", true, "Синяя"),
                LightPurple => new ThemeDescription("Нежная лаванда", true, "Фиолетовая"),
                DarkOrange => new ThemeDescription("Шоколад с апельсином", false, "Оранжевая"),
                DarkGreen => new ThemeDescription("Хвойный лес", false, "Зеленая"),
                _ => new ThemeDescription()
            };
        }

        public static void LoadSavedTheme()
        {
            ApplyTheme(Preferences.Get("user_theme", LightBlue));
        }
    }
}
