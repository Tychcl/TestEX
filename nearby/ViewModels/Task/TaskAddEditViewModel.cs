using System.Collections.ObjectModel;
using System.ComponentModel.DataAnnotations;
using System.Threading.Tasks;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using Microsoft.Maui.Devices.Sensors;
using nearby.Interfaces;
using nearby.Models;
using nearby.Services;
using nearby.Views.Main;

namespace nearby.ViewModels
{
    [QueryProperty(nameof(TaskTemp), "task")]
    public partial class TaskAddEditViewModel : BaseViewModel
    {
        private readonly ITaskService _taskService;
        private readonly IUserService _userService;

        private TaskItem _task;

        [ObservableProperty]
        private TaskItem _taskTemp;
        partial void OnTaskTempChanged(TaskItem item)
        {
            _task = TaskTemp;
            _title = _task.Title;
            _description = _task.Description ?? string.Empty;
            _neededVolunteers = _task.NeededVolunteers;
            _priority = _task.Priority;
            _location = _task.Location ?? string.Empty;
            _reward = _task.Reward;
            _deadline = _task.Deadline;
        }

        [ObservableProperty]
        [NotifyDataErrorInfo]
        [Required(ErrorMessage = "Название обязательно")]
        [MaxLength(100, ErrorMessage = "Максимальная длина 100 символов")]
        private string _title;

        [ObservableProperty]
        private int _descriptionLength;
        [ObservableProperty]
        [NotifyDataErrorInfo]
        [MaxLength(300, ErrorMessage = "Максимальная длина 300 символов")]
        [Required(ErrorMessage = "Описание обязательно")]
        private string _description;
        partial void OnDescriptionChanged(string value)
        {
            _task.Description = value;
            DescriptionLength = value.Length;
        }

        [ObservableProperty]
        [NotifyDataErrorInfo]
        [Range(1, 10, ErrorMessage = "Количество волонтёров должно быть от 1 до 10")]
        private int _neededVolunteers;

        [ObservableProperty]
        [NotifyDataErrorInfo]
        [Required(ErrorMessage = "Приоритет обязателен")]
        private string _priority;

        [ObservableProperty]
        [NotifyDataErrorInfo]
        [Required(ErrorMessage = "Дата окончания обязательна")]
        private DateTime _deadline;

        [ObservableProperty]
        private int _locationLength;
        [ObservableProperty]
        [NotifyDataErrorInfo]
        [Required(ErrorMessage = "Адресс обязателен")]
        [MaxLength(150, ErrorMessage = "Максимальная длина 150 символов")]
        private string _location;
        partial void OnLocationChanged(string value)
        {
            _task.Location = value;
            LocationLength = value.Length;
        }

        [ObservableProperty]
        private decimal _reward;

        partial void OnTitleChanged(string value) => _task.Title = value;
        partial void OnNeededVolunteersChanged(int value) => _task.NeededVolunteers = value;
        partial void OnPriorityChanged(string value) => _task.Priority = value;
        partial void OnRewardChanged(decimal value) => _task.Reward = value;
        partial void OnDeadlineChanged(DateTime value) => _task.Deadline = value;

        public TaskAddEditViewModel(ITaskService ts, IUserService us)
        {
            _taskService = ts;
            _userService = us;
            TaskTemp = new TaskItem();
            ValidateAllProperties();
        }

        [RelayCommand]
        private async Task Save()
        {
            try
            {
                ValidateAllProperties();
                if (HasErrors) return;
                var response = await _taskService.CreateTaskAsync(_task);
                await ShowSuccessfulAsync("Задача была создана");
                await GoBackCommand.ExecuteAsync(null);
            }
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }            
        }
    }
}