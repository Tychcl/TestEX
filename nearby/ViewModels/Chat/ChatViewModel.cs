using System.Collections.ObjectModel;
using System.Windows.Input;
using nearby.Classes;
using nearby.Interfaces;
using nearby.Models;
using nearby.Views.Additional;

namespace nearby.ViewModels;

public class ChatsViewModel : BaseViewModel
{
    private readonly IChatService _chatService;
    private readonly IServiceProvider _serviceProvider;

    private ObservableCollection<Chat> _chats = new();
    private bool _isRefreshing;
    private int _currentPage = 1;
    private bool _hasMorePages = true;
    private bool _isLoading;
    private const int PageSize = 20;

    public ObservableCollection<Chat> Chats
    {
        get => _chats;
        set => SetField(ref _chats, value);
    }

    public bool IsRefreshing
    {
        get => _isRefreshing;
        set => SetField(ref _isRefreshing, value);
    }

    public ICommand LoadChatsCommand { get; }
    public ICommand RefreshCommand { get; }
    public ICommand LoadMoreCommand { get; }
    public ICommand ChatSelectedCommand { get; }
    public ICommand CreateGroupCommand { get; }

    public ChatsViewModel(IChatService chatService, IServiceProvider serviceProvider)
    {
        _chatService = chatService;
        _serviceProvider = serviceProvider;

        LoadChatsCommand = new Command(async () => await LoadChatsAsync(true));
        RefreshCommand = new Command(async () => await LoadChatsAsync(true));
        LoadMoreCommand = new Command(async () => await LoadChatsAsync(false));
        ChatSelectedCommand = new Command<Chat>(async (chat) => await GoToChatDetailAsync(chat));
        CreateGroupCommand = new Command(async () => await CreateGroupAsync());
    }

    private async Task LoadChatsAsync(bool reset)
    {
        if (_isLoading) return;

        if (reset)
        {
            _currentPage = 1;
            _hasMorePages = true;
            Chats.Clear();
        }

        if (!_hasMorePages) return;

        _isLoading = true;
        IsRefreshing = true;

        try
        {
            var response = await _chatService.GetChatsAsync(_currentPage, PageSize);
            if (response.result != true)
            {
                await Application.Current.MainPage.DisplayAlert("Ошибка", response.message ?? "Не удалось загрузить чаты", "OK");
                return;
            }

            if (response.Object != null && response.Object.Any())
            {
                foreach (var chat in response.Object)
                    Chats.Add(chat);
                _currentPage++;

                if (response.Object.Count < PageSize)
                    _hasMorePages = false;
            }
            else
            {
                _hasMorePages = false;
            }
        }
        finally
        {
            _isLoading = false;
            IsRefreshing = false;
        }
    }

    private async Task GoToChatDetailAsync(Chat chat)
    {
        try
        {
            await Shell.Current.GoToAsync(nameof(ChatDetailPage), new Dictionary<string, object?> { { "id", chat.Id } });
        }
        catch
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", "Не удалось открыть чат", "OK");
        }
    }

    private async Task CreateGroupAsync()
    {
        // Здесь можно открыть страницу выбора участников и названия
        await Application.Current.MainPage.DisplayAlert("Создание группы", "Функция в разработке", "OK");
    }
}