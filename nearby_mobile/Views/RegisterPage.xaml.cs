using nearby_mobile.ViewModels;

namespace nearby_mobile.Views;

public partial class RegisterPage : ContentPage
{
	public RegisterPage(RegisterViewModel viewModel)
	{
		InitializeComponent();
        BindingContext = viewModel;
    }
}