using nearby.ViewModels;

namespace nearby.Views.Main;

public partial class TaskDetailPage : ContentPage
{

    public TaskDetailPage(TaskDetailViewModel viewModel)
    {
        BindingContext = viewModel;
        InitializeComponent();
    }
}