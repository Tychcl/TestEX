using nearby_mobile.ViewModels;

namespace nearby_mobile.Views;

[QueryProperty(nameof(TaskId), "taskId")]
public partial class TaskDetailPage : ContentPage
{
    private readonly TaskDetailViewModel _viewModel;

    public TaskDetailPage(TaskDetailViewModel viewModel)
    {
        InitializeComponent();
        _viewModel = viewModel;
        BindingContext = _viewModel;
    }

    public string TaskId
    {
        set
        {
            if (int.TryParse(value, out var id))
                _viewModel.InitializeAsync(id);
        }
    }
}