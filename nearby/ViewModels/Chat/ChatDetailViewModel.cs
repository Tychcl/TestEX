using System.Collections.ObjectModel;
using System.Windows.Input;
using nearby.Classes;
using nearby.Interfaces;
using nearby.Models;
using nearby.Services;

namespace nearby.ViewModels
{
    [QueryProperty(nameof(ChatId), "id")]
    public class ChatDetailViewModel : BaseViewModel, IQueryAttributable
    {
        private readonly IChatService _chatService;
        private readonly IUserService _userService;
        private readonly IServiceProvider _serviceProvider;

        private int _chatId;
        private Chat _chat;
        private ObservableCollection<User> _participants = new();
        private ObservableCollection<Message> _messages = new();
        private string _newMessageText;
        private int _currentPage = 1;
        private bool _hasMoreMessages = true;
        private const int PageSize = 50;

        private bool _isMenuVisible;
        public bool IsMenuVisible
        {
            get => _isMenuVisible;
            set => SetField(ref _isMenuVisible, value);
        }

        private Message _selectedMessage;
        public Message SelectedMessage
        {
            get => _selectedMessage;
            set => SetField(ref _selectedMessage, value);
        }

        public int ChatId
        {
            get => _chatId;
            set => SetField(ref _chatId, value);
        }

        public Chat Chat
        {
            get => _chat;
            set => SetField(ref _chat, value);
        }

        public ObservableCollection<User> Participants
        {
            get => _participants;
            set => SetField(ref _participants, value);
        }

        public ObservableCollection<Message> Messages
        {
            get => _messages;
            set => SetField(ref _messages, value);
        }

        public string NewMessageText
        {
            get => _newMessageText;
            set
            {
                if (SetField(ref _newMessageText, value))
                    (SendMessageCommand as Command)?.ChangeCanExecute();
            }
        }

        public int CurrentUserId => _userService.CurrentUser?.Id ?? 0;

        public ICommand LoadMessagesCommand { get; }
        public ICommand SendMessageCommand { get; }
        public ICommand LoadMoreMessagesCommand { get; }
        public ICommand AddMemberCommand { get; }
        public ICommand RemoveMemberCommand { get; }
        public ICommand EditMessageCommand { get; }
        public ICommand DeleteMessageCommand { get; }
        public ICommand OpenMenuCommand { get; }
        public ICommand CloseMenuCommand { get; }
        public ICommand CopyMessageCommand { get; }

        public ChatDetailViewModel(IChatService chatService, IUserService userService, IServiceProvider serviceProvider)
        {
            _chatService = chatService;
            _userService = userService;
            _serviceProvider = serviceProvider;

            LoadMessagesCommand = new Command(async () => await ExecuteAsync(() => LoadMessagesAsync(true), LoadMessagesCommand));
            SendMessageCommand = new Command(async () => await ExecuteAsync(SendMessageAsync, SendMessageCommand),
                () => !string.IsNullOrWhiteSpace(NewMessageText) && !IsBusy);
            LoadMoreMessagesCommand = new Command(async () => await ExecuteAsync(() => LoadMessagesAsync(false), LoadMoreMessagesCommand));
            AddMemberCommand = new Command(async () => await ExecuteAsync(AddMemberAsync, AddMemberCommand));
            RemoveMemberCommand = new Command<User>(async (user) => await ExecuteAsync(() => RemoveMemberAsync(user), RemoveMemberCommand));
            EditMessageCommand = new Command<Message>(async (message) => await ExecuteAsync(() => EditMessageAsync(message), EditMessageCommand));
            DeleteMessageCommand = new Command<Message>(async (message) => await ExecuteAsync(() => DeleteMessageAsync(message), DeleteMessageCommand));
            OpenMenuCommand = new Command<Message>(async (message) => await ExecuteAsync(() => OpenMenuAsync(message), OpenMenuCommand));
            CloseMenuCommand = new Command(async () => await ExecuteAsync(CloseMenu, CloseMenuCommand));
            CopyMessageCommand = new Command<Message>(async (message) => await ExecuteAsync(() => CopyMessage(message), CopyMessageCommand));
            GoBackCommand = new Command(async () => await ExecuteAsync(GoBackAsync, GoBackCommand));
        }

        public Task InitializationTask { get; private set; }
        public void ApplyQueryAttributes(IDictionary<string, object> query)
        {
            if (query.TryGetValue("id", out var idObj) && idObj is int id)
            {
                _chatId = id;
                InitializationTask = InitializeAsync();
            }
        }

        private async Task InitializeAsync()
        {
            await ExecuteAsync(async () =>
            {
                await LoadChatDetailAsync();
                await LoadMessagesAsync(true);
            }, LoadMessagesCommand, SendMessageCommand);
        }

        private async Task LoadChatDetailAsync()
        {
            var response = await _chatService.GetChatByIdAsync(_chatId, _currentPage, PageSize);
            if (response.result != true)
                throw new Exception(response.message ?? "Не удалось загрузить чат");

            if (response.Data != null)
            {
                Chat = response.Data.Chat;
                Participants.Clear();
                if (response.Data.Participants != null)
                {
                    foreach (var p in response.Data.Participants)
                        Participants.Add(p);
                }
            }
        }

        private async Task LoadMessagesAsync(bool reset)
        {
            if (reset)
            {
                _currentPage = 1;
                _hasMoreMessages = true;
                Messages.Clear();
            }

            if (!_hasMoreMessages) return;

            var response = await _chatService.GetMessagesAsync(_chatId, _currentPage, PageSize);
            if (response.result != true)
                throw new Exception(response.message ?? "Не удалось загрузить сообщения");

            if (response.Data?.Object != null && response.Data.Object.Any())
            {
                var newMessages = response.Data.Object.OrderBy(m => m.CreatedAt).ToList();
                foreach (var msg in newMessages)
                {
                    bool isOwn = msg.SenderId == CurrentUserId;
                    msg.layout = isOwn ? LayoutOptions.End : LayoutOptions.Start;
                    msg.corner = isOwn ? new CornerRadius(15, 15, 15, 0) : new CornerRadius(15, 15, 0, 15);
                    Messages.Add(msg);
                }
                _currentPage++;
                if (response.Data.Object.Count < PageSize)
                    _hasMoreMessages = false;
            }
            else
            {
                _hasMoreMessages = false;
            }
        }

        private async Task SendMessageAsync()
        {
            if (string.IsNullOrWhiteSpace(NewMessageText)) return;

            var messageModel = new MessageSendModel { content_type = "text", content = NewMessageText };
            var response = await _chatService.SendMessageAsync(_chatId, messageModel);
            if (response.result != true)
                throw new Exception(response.message ?? "Не удалось отправить сообщение");

            var newMessage = new Message
            {
                Id = response.Data?.Id ?? 0,
                Content = NewMessageText,
                ContentType = "text",
                CreatedAt = DateTime.UtcNow,
                SenderId = CurrentUserId,
                SenderName = _userService.CurrentUser?.FullName ?? "Вы",
                SenderProfilePicture = _userService.CurrentUser?.ProfilePicture,
                layout = LayoutOptions.End,
                corner = new CornerRadius(15, 15, 15, 0)
            };
            Messages.Add(newMessage);
            NewMessageText = string.Empty;
        }

        private async Task AddMemberAsync()
        {
            var idString = await Application.Current.MainPage.DisplayPromptAsync("Добавить участника", "Введите ID пользователя:");
            if (!int.TryParse(idString, out int userId)) return;

            var result = await _chatService.AddMemberAsync(_chatId, userId);
            if (result.result != true)
                throw new Exception(result.message ?? "Не удалось добавить участника");

            await LoadChatDetailAsync();
        }

        private async Task RemoveMemberAsync(User user)
        {
            var confirm = await Application.Current.MainPage.DisplayAlert("Удаление", $"Удалить {user.FullName} из чата?", "Да", "Нет");
            if (!confirm) return;

            var result = await _chatService.RemoveMemberAsync(_chatId, user.Id);
            if (result.result != true)
                throw new Exception(result.message ?? "Не удалось удалить участника");

            Participants.Remove(user);
        }

        private async Task EditMessageAsync(Message message)
        {
            var newText = await Application.Current.MainPage.DisplayPromptAsync("Редактировать", "", "OK", "Отмена", placeholder: message.Content, maxLength: 500);
            if (string.IsNullOrWhiteSpace(newText)) return;

            var result = await _chatService.EditMessageAsync(message.Id, newText);
            if (result.result != true)
                throw new Exception(result.message ?? "Не удалось редактировать");

            message.Content = newText;
            // Обновляем UI (можно вызвать Refresh)
        }

        private async Task DeleteMessageAsync(Message message)
        {
            var confirm = await Application.Current.MainPage.DisplayAlert("Удаление", "Удалить сообщение?", "Да", "Нет");
            if (!confirm) return;

            var result = await _chatService.DeleteMessageAsync(message.Id);
            if (result.result != true)
                throw new Exception(result.message ?? "Не удалось удалить сообщение");

            Messages.Remove(message);
        }

        private async Task OpenMenuAsync(Message message)
        {
            bool isOwnMessage = message.SenderId == CurrentUserId;

            var actions = new List<string>();
            if (isOwnMessage)
            {
                actions.Add("Редактировать");
                actions.Add("Удалить");
                actions.Add("Копировать текст");
                actions.Add("Отмена");
            }
            else
            {
                actions.Add("Копировать текст");
                actions.Add("Отмена");
            }

            var result = await Application.Current.MainPage.DisplayActionSheet(
                "Действия с сообщением",
                null,
                null,
                actions.ToArray());

            if (result == "Редактировать")
                await EditMessageAsync(message);
            else if (result == "Удалить")
                await DeleteMessageAsync(message);
            else if (result == "Копировать текст")
                await CopyMessage(message);
        }

        private Task CloseMenu()
        {
            IsMenuVisible = false;
            SelectedMessage = null;
            return Task.CompletedTask;
        }

        private async Task CopyMessage(Message message)
        {
            await Clipboard.Default.SetTextAsync(message.Content);
        }
    }
}