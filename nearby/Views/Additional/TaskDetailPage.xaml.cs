using nearby.ViewModels;

namespace nearby.Views.Additional;

public partial class TaskDetailPage : ContentPage
{
    //private readonly TaskDetailViewModel _viewModel;

    public TaskDetailPage(TaskDetailViewModel viewModel)
    {
        //_viewModel = viewModel;
        //BindingContext = viewModel;
        BindingContext = viewModel;
        InitializeComponent();
    }

    //public string TaskId
    //{
    //    set
    //    {
    //        if (int.TryParse(value, out var id))
    //            _viewModel.InitializeAsync(id);
    //    }
    //}
}