using nearby.ViewModels;

namespace nearby.Views.Main;

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
        //if (BindingContext is ChatDetailViewModel vm && vm.InitializationTask != null)
        //{
        //    await vm.InitializationTask;
        //}
    }
}