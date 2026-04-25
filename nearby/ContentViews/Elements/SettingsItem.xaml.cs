namespace nearby.ContentViews.Elements;

public partial class SettingsItem : ContentView
{
    public static readonly BindableProperty ImageProperty =
    BindableProperty.Create(nameof(Image), typeof(string), typeof(SettingsItem),
        default(string), BindingMode.OneWay);
    public string Image
    {
        get => (string)GetValue(ImageProperty);
        set => SetValue(ImageProperty, value);
    }

    public static readonly BindableProperty TextProperty =
    BindableProperty.Create(nameof(Text), typeof(string), typeof(SettingsItem),
        default(string), BindingMode.OneWay);
    public string Text
    {
        get => (string)GetValue(TextProperty);
        set => SetValue(TextProperty, value);
    }

    public SettingsItem()
	{
		InitializeComponent();
	}
}