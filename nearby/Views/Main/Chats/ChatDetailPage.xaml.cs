using nearby.ViewModels;

namespace nearby.Views.Main;

public partial class ChatDetailPage : ContentPage
{
    public ChatDetailPage(ChatDetailViewModel viewModel)
    {
        InitializeComponent();
        BindingContext = viewModel;
    }

    //protected override async void OnAppearing()
    //{
    //    base.OnAppearing();
    //    if (BindingContext is ChatDetailViewModel vm)
    //    {
    //        if (vm.Messages.Count > 0)
    //        {
    //            await Task.Delay(300);
    //            ChatCollectionView.ScrollTo(vm.Messages.Last(), position: ScrollToPosition.MakeVisible, animate: false);
    //        }
    //    }
    //}
}