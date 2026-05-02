using System.Windows.Input;

namespace nearby.ContentViews.Headers
{
    public partial class BaseHeaderView : ContentView
    {
        public static readonly BindableProperty TextProperty =
        BindableProperty.Create(nameof(Text), typeof(string), typeof(BaseHeaderView),
            default(string), BindingMode.OneWay);
        public string Text
        {
            get => (string)GetValue(TextProperty);
            set => SetValue(TextProperty, value);
        }

        public static readonly BindableProperty BackCommandProperty =
        BindableProperty.Create(nameof(BackCommand), typeof(ICommand), typeof(BaseHeaderView),
            default(ICommand), BindingMode.OneWay);
        public ICommand BackCommand
        {
            get => (ICommand)GetValue(BackCommandProperty);
            set => SetValue(BackCommandProperty, value);
        }

        public static readonly BindableProperty CommandProperty =
        BindableProperty.Create(nameof(Command), typeof(ICommand), typeof(BaseHeaderView),
            null, BindingMode.OneWay);
        public ICommand Command
        {
            get => (ICommand)GetValue(CommandProperty);
            set => SetValue(CommandProperty, value);
        }

        public static readonly BindableProperty MenuIsVisibleProperty =
        BindableProperty.Create(nameof(MenuIsVisible), typeof(bool), typeof(BaseHeaderView),
            default(bool), BindingMode.OneWay);
        public bool MenuIsVisible
        {
            get => (bool)GetValue(MenuIsVisibleProperty);
            set => SetValue(MenuIsVisibleProperty, value);
        }

        public static readonly BindableProperty IconProperty =
        BindableProperty.Create(nameof(Icon), typeof(string), typeof(BaseHeaderView),
            default(string), BindingMode.OneWay);
        public string Icon
        {
            get => (string)GetValue(IconProperty);
            set => SetValue(IconProperty, value);
        }

        public BaseHeaderView()
        {
            InitializeComponent();
        }
    }
}