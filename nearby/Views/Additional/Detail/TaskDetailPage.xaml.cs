using nearby.ViewModels;

namespace nearby.Views.Additional;

public partial class TaskDetailPage : ContentPage
{

    public TaskDetailPage(TaskDetailViewModel viewModel)
    {
        BindingContext = viewModel;
        InitializeComponent();
    }

    protected override async void OnAppearing()
    {
        base.OnAppearing();
        if (BindingContext is TaskDetailViewModel vm && vm.InitializationTask != null)
        {
            await vm.InitializationTask;
        }
    }
}