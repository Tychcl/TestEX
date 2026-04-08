using nearby.ViewModels;

namespace nearby.Views.Additional;

public partial class ChatDetailPage : ContentPage
{
    public ChatDetailPage(ChatDetailViewModel viewModel)
    {
        BindingContext = viewModel;
        InitializeComponent();
    }

    protected override async void OnAppearing()
    {
        base.OnAppearing();
    }
}