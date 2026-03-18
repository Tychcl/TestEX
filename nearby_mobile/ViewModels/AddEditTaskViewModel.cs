using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Windows.Input;
using nearby_mobile.Interfaces;
using Newtonsoft.Json;
using System.Text;
using nearby_mobile.Services;

namespace nearby_mobile.ViewModels;

public class AddEditTaskViewModel : INotifyPropertyChanged
{
    private readonly IUserService _userService;
    private readonly IServiceProvider _serviceProvider;
    private readonly ApiClient _apiClient;

    private string _title = string.Empty;
    private string _description = string.Empty;
    private int _neededVolunteers = 1;
    private string _priority = "medium";
    private string _location = string.Empty;
    private decimal _reward = 0;
    private DateTime _deadline = DateTime.Today.AddDays(1);

    public string Title
    {
        get => _title;
        set { if (_title != value) { _title = value; OnPropertyChanged(); } }
    }

    public string Description
    {
        get => _description;
        set { if (_description != value) { _description = value; OnPropertyChanged(); } }
    }

    public int NeededVolunteers
    {
        get => _neededVolunteers;
        set { if (_neededVolunteers != value) { _neededVolunteers = value; OnPropertyChanged(); } }
    }

    public string Priority
    {
        get => _priority;
        set { if (_priority != value) { _priority = value; OnPropertyChanged(); } }
    }

    public string Location
    {
        get => _location;
        set { if (_location != value) { _location = value; OnPropertyChanged(); } }
    }

    public decimal Reward
    {
        get => _reward;
        set { if (_reward != value) { _reward = value; OnPropertyChanged(); } }
    }

    public DateTime Deadline
    {
        get => _deadline;
        set { if (_deadline != value) { _deadline = value; OnPropertyChanged(); } }
    }

    public List<string> Priorities { get; } = new() { "low", "medium", "high" };
    public DateTime Today => DateTime.Today;

    public ICommand SaveCommand { get; }
    public ICommand CancelCommand { get; }

    public AddEditTaskViewModel(IUserService userService, ApiClient apiClient, IServiceProvider serviceProvider)
    {
        _userService = userService;
        _apiClient = apiClient;
        SaveCommand = new Command(async () => await SaveAsync());
        CancelCommand = new Command(async () => await CancelAsync());
        _serviceProvider = serviceProvider;
    }

    private async Task SaveAsync()
    {
        // Валидация
        if (string.IsNullOrWhiteSpace(Title))
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Введите название задачи", "OK");
            return;
        }
        if (NeededVolunteers < 1)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Количество волонтёров должно быть не менее 1", "OK");
            return;
        }
        if (Deadline <= DateTime.Now)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Дедлайн должен быть в будущем", "OK");
            return;
        }

        var taskData = new
        {
            title = Title,
            description = Description,
            needed_volunteers = NeededVolunteers,
            priority = Priority,
            location = Location,
            reward = Reward,
            deadline = Deadline.ToString("yyyy-MM-dd HH:mm:ss")
        };

        try
        {
            var json = JsonConvert.SerializeObject(taskData);
            var content = new StringContent(json, Encoding.UTF8, "application/json");
            var response = await _apiClient.PostAsync("tasks", content);
            if (response.IsSuccessStatusCode)
            {
                await Application.Current.MainPage.DisplayAlert("Успех", "Задача создана", "OK");
                // Вернуться назад
                if (Shell.Current != null)
                    await Shell.Current.GoToAsync("..");
                else
                    await Application.Current.MainPage.Navigation.PopAsync();
            }
            else
            {
                var error = await response.Content.ReadAsStringAsync();
                await Application.Current.MainPage.DisplayAlert("Ошибка", error, "OK");
            }
        }
        catch (Exception ex)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", ex.Message, "OK");
        }
    }

    private async Task CancelAsync()
    {
        if (Shell.Current != null)
            await Shell.Current.GoToAsync("..");
        else
            await Application.Current.MainPage.Navigation.PopAsync();
    }

    public async Task InitializeForEditAsync(int taskId)
    {
        var taskService = _serviceProvider.GetRequiredService<ITaskService>();
        var task = await taskService.GetTaskAsync(taskId);
        if (task != null)
        {
            Title = task.Title;
            Description = task.Description;
            NeededVolunteers = task.NeededVolunteers;
            Priority = task.Priority;
            Location = task.Location;
            Reward = task.Reward;
            Deadline = task.Deadline;
        }
    }

    public event PropertyChangedEventHandler? PropertyChanged;
    protected virtual void OnPropertyChanged([CallerMemberName] string propertyName = null)
    {
        PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
    }
}