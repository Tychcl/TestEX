using nearby_mobile.ViewModels;

namespace nearby_mobile.Views;

public partial class LoginPage : ContentPage
{
	public LoginPage(LoginViewModel viewModel)
	{
		InitializeComponent();
        BindingContext = viewModel;
    }
}