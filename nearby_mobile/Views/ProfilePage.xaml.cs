using nearby_mobile.ViewModels;

namespace nearby_mobile.Views;

public partial class ProfilePage : ContentPage
{
	public ProfilePage(ProfileViewModel viewModel)
	{
		InitializeComponent();
        BindingContext = viewModel;
    }

}