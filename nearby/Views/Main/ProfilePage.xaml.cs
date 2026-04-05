using System.Diagnostics;
using System.Threading.Tasks;
using nearby.ViewModels;

namespace nearby.Views.Main;

public partial class ProfilePage : ContentPage
{
    private readonly ProfileViewModel _viewModel;

    public ProfilePage(ProfileViewModel viewModel)
    {
        _viewModel = viewModel;
        BindingContext = _viewModel;
        InitializeComponent();
    }

    protected override async void OnAppearing()
    {
        base.OnAppearing();
        if (_viewModel is not null)
        {
            await _viewModel.LoadData();
        }
    }

    protected override void OnDisappearing()
    {
        base.OnDisappearing();
        (BindingContext as IDisposable)?.Dispose();
    }
}