using System.Collections.ObjectModel;
using System.Windows.Input;
using nearby.Classes;
using nearby.Interfaces;
using nearby.Models;
using nearby.Views.Additional;

namespace nearby.ViewModels
{
    public class ChatsViewModel : BaseViewModel
    {
        private readonly IChatService _chatService;
        private readonly IServiceProvider _serviceProvider;

        private ObservableCollection<Chat> _chats = new();
        private bool _isRefreshing;
        private int _currentPage = 1;
        private bool _hasMorePages = true;
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

            LoadChatsCommand = new Command(async () => await ExecuteAsync(() => LoadChatsAsync(true), LoadChatsCommand));
            RefreshCommand = new Command(async () => await ExecuteAsync(() => LoadChatsAsync(true), RefreshCommand), () => !IsBusy);
            LoadMoreCommand = new Command(async () => await ExecuteAsync(() => LoadChatsAsync(false), LoadMoreCommand), () => !IsBusy && _hasMorePages);
            ChatSelectedCommand = new Command<Chat>(async (chat) => await ExecuteAsync(() => GoToChatDetailAsync(chat), ChatSelectedCommand));
            CreateGroupCommand = new Command(async () => await ExecuteAsync(CreateGroupAsync, CreateGroupCommand));
        }

        private async Task LoadChatsAsync(bool reset)
        {
            if (reset)
            {
                _currentPage = 1;
                _hasMorePages = true;
                Chats.Clear();
            }

            if (!_hasMorePages) return;

            IsRefreshing = true;
            try
            {
                var response = await _chatService.GetChatsAsync(_currentPage, PageSize);
                if (response.result != true)
                    throw new Exception(response.message ?? "Не удалось загрузить чаты");

                if (response.Data != null && response.Data.Any())
                {
                    foreach (var chat in response.Data)
                        Chats.Add(chat);
                    _currentPage++;
                    if (response.Data.Count < PageSize)
                        _hasMorePages = false;
                }
                else
                {
                    _hasMorePages = false;
                }
            }
            finally
            {
                IsRefreshing = false;
            }
        }

        private async Task GoToChatDetailAsync(Chat chat)
        {
            await Shell.Current.GoToAsync(nameof(ChatDetailPage), new Dictionary<string, object?> { { "id", chat.Id } });
        }

        private async Task CreateGroupAsync()
        {
            await Application.Current.MainPage.DisplayAlert("Создание группы", "Функция в разработке", "OK");
        }
    }
}