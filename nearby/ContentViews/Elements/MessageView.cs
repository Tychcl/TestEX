using System.Windows.Input;
using CommunityToolkit.Mvvm.Input;
using nearby.Classes;
using nearby.Classes.Interface.Converters;
using nearby.Models;
using nearby.Services;

namespace nearby.ContentViews.Elements;

public class MessageView : ContentView
{
    public static readonly BindableProperty CurrentUserIdProperty =
    BindableProperty.Create(nameof(CurrentUserId), typeof(int), typeof(MessageView), -1, propertyChanged: OnMessageChanged);
    public int CurrentUserId
    {
        get => (int)GetValue(CurrentUserIdProperty);
        set => SetValue(CurrentUserIdProperty, value);
    }

    public static readonly BindableProperty IsOwnMessageProperty =
    BindableProperty.Create(nameof(IsOwnMessage), typeof(bool), typeof(MessageView), false);
    public bool IsOwnMessage
    {
        get => (bool)GetValue(IsOwnMessageProperty);
        set => SetValue(IsOwnMessageProperty, value);
    }

    public static readonly BindableProperty CommandProperty =
        BindableProperty.Create(nameof(Command), typeof(IAsyncRelayCommand), typeof(MessageView), null, BindingMode.OneWay, propertyChanged: OnCommandChanged);
    public IAsyncRelayCommand Command
    {
        get => (IAsyncRelayCommand)GetValue(CommandProperty);
        set => SetValue(CommandProperty, value);
    }
    private static void OnCommandChanged(BindableObject bindable, object oldValue, object newValue)
    {
        var view = (MessageView)bindable;
        var TGR = new TapGestureRecognizer();
        TGR.Tapped += async (s, e) =>
        {
            view.point = e.GetPosition(null);
            await view.Command.ExecuteAsync(view);
        };
        view.Content.GestureRecognizers.Add(TGR);
    }

    public static readonly BindableProperty MessageProperty = 
		BindableProperty.Create(nameof(Message), typeof(Message), typeof(MessageView), null, propertyChanged: OnMessageChanged);
    public Message? Message
    {
        get => (Message?)GetValue(MessageProperty);
        set => SetValue(MessageProperty, value);
    }
    private static void OnMessageChanged(BindableObject bindable, object oldValue, object newValue)
    {
        var view = (MessageView)bindable;
        view.IsOwnMessage = view.Message?.SenderId == view.CurrentUserId;
    }

    private Label _content;
    private Label _date;
    private Grid _grid;
    public Point? point;
    public MessageView()
	{
        Style LS = (Style)ResourceManager.Get("CommonLabel");
        _date = new Label()
        {
            Style = LS,
            HorizontalOptions = LayoutOptions.End,
            Margin = new Thickness(10, 5, 0, 0),
            FontSize = 12
        };
        _date.SetBinding(Label.TextProperty, new Binding(nameof(Message.CreatedAt), stringFormat: "{0:dd.MM HH:mm}"));
        _date.SetDynamicResource(Label.TextColorProperty, "CTextSecondary");

        _content = new Label()
        {
            Style = LS,
            HorizontalOptions = LayoutOptions.Start
        };
        _content.SetBinding(Label.TextProperty, new Binding(nameof(Message.Content)));

        _grid = new Grid
        {
            Padding = 5,
            RowDefinitions = new RowDefinitionCollection
                {
                    new() { Height = new GridLength(1, GridUnitType.Auto) },
                    new() { Height = new GridLength(1, GridUnitType.Auto) }
                },
            MaximumWidthRequest = 300
        };
        _grid.Add( _content, row: 0);
        _grid.Add( _date, row: 1);

        Content = new Border
        {
            Style = (Style)ResourceManager.Get("EditSection"),
            Margin = new Thickness(5),
            Padding = 0,
            Content = _grid,
            HorizontalOptions = LayoutOptions.End,
        };
        Content.SetDynamicResource(Border.BackgroundProperty, "CSurface");
        Content.SetBinding(Border.StrokeShapeProperty, new Binding(nameof(IsOwnMessage), source: this, converter: new MessageCornerConverter()));
        Content.SetBinding(Border.HorizontalOptionsProperty, new Binding(nameof(IsOwnMessage), source: this, converter: new MessageAlignmentConverter()));
        Content.SetBinding(BindableObject.BindingContextProperty, new Binding(nameof(Message), source: this));
    }
}