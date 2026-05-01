using System.Reflection;
using nearby.Classes;

namespace nearby.Views.Main;

public partial class ThemeChangePage : ContentPage
{
    List<string> resources = new();
	public ThemeChangePage()
	{
		InitializeComponent();
	}

    protected override void OnAppearing()
    {
        base.OnAppearing();
        var themes = typeof(ThemeManager).GetFields();
        foreach (var t in themes)
        {
            resources.Add(t.Name);
        }
        BindingContext = resources;
    }
}