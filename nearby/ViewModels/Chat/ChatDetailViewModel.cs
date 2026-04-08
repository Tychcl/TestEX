using System.Collections.ObjectModel;
using System.Windows.Input;
using nearby.Classes;
using nearby.Interfaces;
using nearby.Models;
using nearby.Services;

namespace nearby.ViewModels;

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
    private bool _isLoadingMessages;
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
            SetField(ref _newMessageText, value);
            (SendMessageCommand as Command)?.ChangeCanExecute();
        }
    }

    public bool IsLoadingMessages
    {
        get => _isLoadingMessages;
        set => SetField(ref _isLoadingMessages, value);
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
  
        LoadMessagesCommand = new Command(async () => await LoadMessagesAsync(true));
        SendMessageCommand = new Command(async () => await SendMessageAsync(), () => !string.IsNullOrWhiteSpace(NewMessageText));
        LoadMoreMessagesCommand = new Command(async () => await LoadMessagesAsync(false));
        AddMemberCommand = new Command(async () => await AddMemberAsync());
        RemoveMemberCommand = new Command<User>(async (user) => await RemoveMemberAsync(user));
        EditMessageCommand = new Command<Message>(async (message) => await EditMessageAsync(message));
        DeleteMessageCommand = new Command<Message>(async (message) => await DeleteMessageAsync(message));
        OpenMenuCommand = new Command<Message>(async (message) => await OpenMenuAsync(message));
        CloseMenuCommand = new Command<Message>(async (message) => await CloseMenu());
        CopyMessageCommand = new Command<Message>(async (message) => await CopyMessage(message));
        GoBackCommand = new Command(async () => await GoBackAsync());
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

    private async Task CloseMenu()
    {
        IsMenuVisible = false;
        SelectedMessage = null;
    }

    public void ApplyQueryAttributes(IDictionary<string, object> query)
    {
        if (query.TryGetValue("id", out var idObj) && idObj is int id)
        {
            _chatId = id;
            _ = LoadChatDetailAsync();
            _ = LoadMessagesAsync(true);    
        }
    }

    private async Task LoadChatDetailAsync()
    {
        try
        {
            var response = await _chatService.GetChatByIdAsync(_chatId, _currentPage, PageSize);
            if (response.result != true)
            {
                await Application.Current.MainPage.DisplayAlert("Ошибка", response.message ?? "Не удалось загрузить чат", "OK");
                await GoBackAsync();
                return;
            }

            if (response.Object != null)
            {
                Chat = response.Object.Chat;
                Participants.Clear();
                if (response.Object.Participants != null)
                {
                    foreach (var p in response.Object.Participants)
                        Participants.Add(p);
                }
            }
        }
        catch (Exception ex)
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", ex.Message, "OK");
        }
    }

    private async Task LoadMessagesAsync(bool reset)
    {
        if (IsLoadingMessages) return;

        if (reset)
        {
            _currentPage = 1;
            _hasMoreMessages = true;
            Messages.Clear();
        }

        if (!_hasMoreMessages) return;

        IsLoadingMessages = true;
        try
        {
            var response = await _chatService.GetMessagesAsync(_chatId, _currentPage, PageSize);
            if (response.result != true)
            {
                await Application.Current.MainPage.DisplayAlert("Ошибка", response.message ?? "Не удалось загрузить сообщения", "OK");
                return;
            }

            if (response.Object?.Object != null && response.Object.Object.Any())
            {
                var newMessages = response.Object.Object.OrderBy(m => m.CreatedAt).ToList();
                foreach (var msg in newMessages)
                {
                    bool isOwnMessage = msg.SenderId == CurrentUserId;
                    msg.layout = isOwnMessage ? LayoutOptions.End : LayoutOptions.Start;
                    msg.corner = isOwnMessage ? new CornerRadius(15, 15, 15, 0) : new CornerRadius(15, 15, 0, 15);
                    Messages.Add(msg);
                }

                _currentPage++;

                if (response.Object.Object.Count < PageSize)
                    _hasMoreMessages = false;
            }
            else
            {
                _hasMoreMessages = false;
            }
        }
        finally
        {
            IsLoadingMessages = false;
        }
    }

    private async Task SendMessageAsync()
    {
        if (string.IsNullOrWhiteSpace(NewMessageText)) return;

        var messageModel = new MessageSendModel
        {
            content_type = "text",
            content = NewMessageText
        };

        var response = await _chatService.SendMessageAsync(_chatId, messageModel);
        if (response.result == true)
        {
            var newMessage = new Message
            {
                Id = response.Object?.Id ?? 0,
                Content = NewMessageText,
                ContentType = "text",
                CreatedAt = DateTime.UtcNow,
                SenderId = CurrentUserId,
                SenderName = _userService.CurrentUser?.FullName ?? "Вы",
                SenderProfilePicture = _userService.CurrentUser?.ProfilePicture
            };
            newMessage.layout = LayoutOptions.End;
            newMessage.corner = new CornerRadius(15, 15, 15, 0);
            Messages.Add(newMessage);
            NewMessageText = "";
        }
        else
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", response.message ?? "Не удалось отправить сообщение", "OK");
        }
    }

    private async Task AddMemberAsync()
    {
        var idString = await Application.Current.MainPage.DisplayPromptAsync("Добавить участника", "Введите ID пользователя:");
        if (int.TryParse(idString, out int userId))
        {
            var result = await _chatService.AddMemberAsync(_chatId, userId);
            if (result.result == true)
            {
                await LoadChatDetailAsync();
            }
            else
            {
                await Application.Current.MainPage.DisplayAlert("Ошибка", result.message ?? "Не удалось добавить участника", "OK");
            }
        }
    }

    private async Task RemoveMemberAsync(User user)
    {
        var confirm = await Application.Current.MainPage.DisplayAlert("Удаление", $"Удалить {user.FullName} из чата?", "Да", "Нет");
        if (!confirm) return;

        var result = await _chatService.RemoveMemberAsync(_chatId, user.Id);
        if (result.result == true)
        {
            Participants.Remove(user);
        }
        else
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", result.message ?? "Не удалось удалить участника", "OK");
        }
    }

    private async Task EditMessageAsync(Message message)
    {
        var newText = await Application.Current.MainPage.DisplayPromptAsync("Редактировать", "", "OK", "Отмена", placeholder: message.Content, maxLength: 500);
        if (!string.IsNullOrWhiteSpace(newText))
        {
            var result = await _chatService.EditMessageAsync(message.Id, newText);
            if (result.result == true)
            {
                message.Content = newText;
                var index = Messages.IndexOf(message);
                if (index >= 0) Messages[index] = message;
            }
            else
            {
                await Application.Current.MainPage.DisplayAlert("Ошибка", result.message ?? "Не удалось редактировать", "OK");
            }
            await CloseMenu();
        }
    }

    private async Task DeleteMessageAsync(Message message)
    {
        var confirm = await Application.Current.MainPage.DisplayAlert("Удаление", "Удалить сообщение?", "Да", "Нет");
        if (!confirm) return;

        var result = await _chatService.DeleteMessageAsync(message.Id);
        if (result.result == true)
        {
            Messages.Remove(message);
        }
        else
        {
            await Application.Current.MainPage.DisplayAlert("Ошибка", result.message ?? "Не удалось удалить сообщение", "OK");
        }
        await CloseMenu();
    }

    private async Task CopyMessage(Message message)
    {
        await Clipboard.Default.SetTextAsync(message.Content);
        await CloseMenu();
    }

    private async Task GoBackAsync()
    {
        await Shell.Current.GoToAsync("..");
    }
}