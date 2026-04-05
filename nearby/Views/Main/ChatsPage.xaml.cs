using nearby.ViewModels;

namespace nearby.Views.Main;

public partial class ChatsPage : ContentPage
{
    public ChatsPage(ChatsViewModel viewModel)
    {
        BindingContext = viewModel;
        InitializeComponent();
    }

    protected override async void OnAppearing()
    {
        base.OnAppearing();
        if (BindingContext is ChatsViewModel vm && vm.Chats.Count == 0)
        {
            vm.LoadChatsCommand.Execute(null);
        }
    }
}