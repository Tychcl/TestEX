using Microsoft.Maui.Controls;

namespace nearby.Classes.Interface.Behaviors;

public class ValidationBehavior : Behavior<Entry>
{
    public static readonly BindableProperty IsValidProperty =
    BindableProperty.Create(
        nameof(IsValid),
        typeof(bool),
        typeof(ValidationBehavior),
        false,
        BindingMode.TwoWay);

    public bool IsValid
    {
        get => (bool)GetValue(IsValidProperty);
        set => SetValue(IsValidProperty, value);
    }

    public static readonly BindableProperty ValidateFuncProperty =
        BindableProperty.Create(
            nameof(ValidateFunc),
            typeof(Func<string, bool>),
            typeof(ValidationBehavior),
            null);

    public Func<string, bool> ValidateFunc
    {
        get => (Func<string, bool>)GetValue(ValidateFuncProperty);
        set => SetValue(ValidateFuncProperty, value);
    }

    private EventHandler _bindingContextChangedHandler;
    protected override void OnAttachedTo(Entry entry)
    {
        base.OnAttachedTo(entry);
        this.BindingContext = entry.BindingContext;
        _bindingContextChangedHandler = (s, e) => this.BindingContext = entry.BindingContext;
        entry.BindingContextChanged += _bindingContextChangedHandler;
        entry.TextChanged += OnTextChanged;
    }
    protected override void OnDetachingFrom(Entry entry)
    {
        entry.TextChanged -= OnTextChanged;
        entry.BindingContextChanged -= _bindingContextChangedHandler;
        base.OnDetachingFrom(entry);
    }

    private void OnEntryBindingContextChanged(object sender, EventArgs e)
    {
        if (sender is Entry entry)
        {
            this.BindingContext = entry.BindingContext;
            ValidateEntry(entry, entry.Text);
        }
    }

    private void OnTextChanged(object sender, TextChangedEventArgs e)
    {
        if (sender is Entry entry)
            ValidateEntry(entry, e.NewTextValue);
    }

    private void ValidateEntry(Entry entry, string text)
    {
        bool isValid = ValidateFunc(text);
        entry.TextColor = isValid ? Colors.Black : Colors.Red;
        IsValid = isValid;
    }
}