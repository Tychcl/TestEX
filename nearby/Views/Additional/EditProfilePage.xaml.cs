using nearby.ViewModels;

namespace nearby.Views.Additional;

public partial class EditProfilePage : ContentPage
{
	public EditProfilePage(EditProfileViewModel viewModel)
	{
		InitializeComponent();
        BindingContext = viewModel;
    }
}