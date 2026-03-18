using nearby_mobile.ViewModels;

namespace nearby_mobile.Views;

public partial class AddEditTaskPage : ContentPage
{
    public AddEditTaskPage(AddEditTaskViewModel viewModel)
    {
        InitializeComponent();
        BindingContext = viewModel;
    }

    private async void OnBackButtonClicked(object sender, EventArgs e)
    {
        await Navigation.PopAsync();
    }
}