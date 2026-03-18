using System.Diagnostics;
using nearby_mobile.ViewModels;

namespace nearby_mobile.Views;

public partial class ProfilePage : ContentPage
{
    private readonly ProfileViewModel _viewModel;

    public ProfilePage(ProfileViewModel viewModel)
    {
        InitializeComponent();
        _viewModel = viewModel;
        BindingContext = _viewModel;
    }

    public async Task InitializeAsync(int userId)
    {
        await _viewModel.InitializeAsync(userId);
    }

    protected override async void OnAppearing()
    {
        base.OnAppearing();
        if (!_viewModel.IsInitialized)
        {
            await _viewModel.InitializeAsync(null);
        }
    }

    protected override void OnDisappearing()
    {
        base.OnDisappearing();
        (BindingContext as IDisposable)?.Dispose();
    }
}