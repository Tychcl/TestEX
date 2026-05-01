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

        public static readonly BindableProperty ConfirmCommandProperty =
        BindableProperty.Create(nameof(ConfirmCommand), typeof(ICommand), typeof(BaseHeaderView),
            null, BindingMode.OneWay);
        public ICommand ConfirmCommand
        {
            get => (ICommand)GetValue(ConfirmCommandProperty);
            set => SetValue(ConfirmCommandProperty, value);
        }

        public static readonly BindableProperty HaveCCProperty =
        BindableProperty.Create(nameof(HaveCC), typeof(bool), typeof(BaseHeaderView),
            default(bool), BindingMode.OneWay);
        public bool HaveCC
        {
            get => (bool)GetValue(HaveCCProperty);
            set => SetValue(HaveCCProperty, value);
        }

        public BaseHeaderView()
        {
            InitializeComponent();
        }
    }
}