using nearby.ViewModels;

namespace nearby.Views.Additional;

public partial class TaskDetailPage : ContentPage
{

    public TaskDetailPage(TaskDetailViewModel viewModel)
    {
        BindingContext = viewModel;
        InitializeComponent();
    }
}