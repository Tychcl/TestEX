using nearby_mobile.ViewModels;

namespace nearby_mobile.Views;

public partial class EditProfilePage : ContentPage
{
	public EditProfilePage(EditProfileViewModel viewModel)
	{
		InitializeComponent();
        BindingContext = viewModel;
    }
}