using System.Collections.ObjectModel;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;

using nearby.Interfaces;
using nearby.Models;
using nearby.Services;

namespace nearby.ViewModels
{
    [QueryProperty(nameof(ChatId), "id")]
    public partial class ChatDetailViewModel : BaseViewModel
    {
        private readonly IChatService _chatService;
        private readonly IUserService _userService;

        private const int PageSize = 50;
        private int _currentPage = 1;
        private bool _hasMoreMessages = true;

        [ObservableProperty]
        private int _chatId;

        [ObservableProperty]
        private Chat? _chat;

        [ObservableProperty]
        private ObservableCollection<User> _participants = new();

        [ObservableProperty]
        private ObservableCollection<Message> _messages = new();

        [ObservableProperty]
        private string _newMessageText = string.Empty;

        [ObservableProperty]
        private bool _isMenuVisible;

        [ObservableProperty]
        private Message? _selectedMessage;

        public int CurrentUserId => _userService.CurrentUser?.Id ?? 0;

        public ChatDetailViewModel(IChatService chatService, IUserService userService)
        {
            _chatService = chatService;
            _userService = userService;
        }

        async partial void OnChatIdChanged(int value)
        {
            if (value > 0)
            {
                IsBusy = true;
                try
                {
                    await LoadChatDetailAsync();
                    await LoadMessagesBaseAsync(true);
                }
                finally
                {
                    IsBusy = false;
                }
            }
        }

        partial void OnChatChanged(Chat? value)
        {
            if (value != null)
                PageTitle = value.OtherUser?.FullName ?? "Чат";
        }
        protected override void OnBusyStateChanged(bool isBusy)
        {
            base.OnBusyStateChanged(isBusy);
            RefreshCommands();
        }
        partial void OnNewMessageTextChanged(string value) => SendMessageCommand.NotifyCanExecuteChanged();

        private async Task LoadChatDetailAsync()
        {
            try
            {
                var response = await _chatService.GetChatByIdAsync(ChatId, _currentPage, PageSize);
                if (response.Data != null)
                {
                    Chat = response.Data.Chat;
                    Participants.Clear();
                    if (response.Data.Participants != null)
                        foreach (var p in response.Data.Participants)
                            Participants.Add(p);
                }
            }
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }
        }

        [RelayCommand]
        private async Task LoadMessages() => await LoadMessagesBaseAsync(true);

        [RelayCommand]
        private async Task LoadMoreMessages() => await LoadMessagesBaseAsync(false);

        private async Task LoadMessagesBaseAsync(bool reset)
        {
            try
            {
                if (reset)
                {
                    _currentPage = 1;
                    _hasMoreMessages = true;
                    Messages.Clear();
                }

                if (!_hasMoreMessages) return;

                var response = await _chatService.GetMessagesAsync(ChatId, _currentPage, PageSize);
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
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }
        }

        [RelayCommand(CanExecute = nameof(CanSendMessage))]
        private async Task SendMessage()
        {
            try
            {
                if (string.IsNullOrWhiteSpace(NewMessageText)) return;

                var messageModel = new MessageSendModel { content_type = "text", content = NewMessageText };
                var response = await _chatService.SendMessageAsync(ChatId, messageModel);

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
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }
        }
        private bool CanSendMessage() => !string.IsNullOrWhiteSpace(NewMessageText) && !IsBusy;

        [RelayCommand]
        private async Task AddMember()
        {
            try
            {
                var idString = await Application.Current!.MainPage!.DisplayPromptAsync("Добавить участника", "Введите ID пользователя:");
                if (!int.TryParse(idString, out int userId)) return;
                var result = await _chatService.AddMemberAsync(ChatId, userId);
                await LoadChatDetailAsync();
            }
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }
        }

        [RelayCommand(CanExecute = nameof(CanModifyMember))]
        private async Task RemoveMember(User user)
        {
            try
            {
                var confirm = await Application.Current!.MainPage!.DisplayAlert("Удаление", $"Удалить {user.FullName} из чата?", "Да", "Нет");
                if (!confirm) return;

                var result = await _chatService.RemoveMemberAsync(ChatId, user.Id);
                if (result.result != true)
                    throw new Exception(result.message ?? "Не удалось удалить участника");

                Participants.Remove(user);
            }
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }
        }
        private bool CanModifyMember() => !IsBusy;

        [RelayCommand(CanExecute = nameof(CanModifyMessage))]
        private async Task EditMessage(Message? message)
        {
            try
            {
                if (message == null) return;
                var newText = await Application.Current!.MainPage!.DisplayPromptAsync("Редактировать", "", "OK", "Отмена", placeholder: message.Content, maxLength: 500);
                if (string.IsNullOrWhiteSpace(newText)) return;
                var result = await _chatService.EditMessageAsync(message.Id, newText);
                message.Content = newText;
                // Для обновления UI можно заменить объект в ObservableCollection
                var index = Messages.IndexOf(message);
                if (index >= 0)
                    Messages[index] = message;
            }
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }
        }

        [RelayCommand(CanExecute = nameof(CanModifyMessage))]
        private async Task DeleteMessage(Message? message)
        {
            try
            {
                if (message == null) return;
                var confirm = await Application.Current!.MainPage!.DisplayAlert("Удаление", "Удалить сообщение?", "Да", "Нет");
                if (!confirm) return;
                var result = await _chatService.DeleteMessageAsync(message.Id);
                Messages.Remove(message);
            }
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }
        }
        private bool CanModifyMessage() => !IsBusy;

        [RelayCommand]
        private async Task OpenMenu(Message? message)
        {
            if (message == null) return;
            bool isOwnMessage = message.SenderId == CurrentUserId;

            var actions = new List<string>();
            if (isOwnMessage)
            {
                actions.Add("Редактировать");
                actions.Add("Удалить");
            }
            actions.Add("Копировать текст");
            actions.Add("Отмена");

            var result = await Application.Current!.MainPage!.DisplayActionSheet(
                "Действия с сообщением", null, null, actions.ToArray());

            if (result == "Редактировать")
                await EditMessageCommand.ExecuteAsync(message);
            else if (result == "Удалить")
                await DeleteMessageCommand.ExecuteAsync(message);
            else if (result == "Копировать текст")
                await CopyMessage(message);
        }

        [RelayCommand]
        private Task CloseMenu()
        {
            IsMenuVisible = false;
            SelectedMessage = null;
            return Task.CompletedTask;
        }

        [RelayCommand]
        private async Task CopyMessage(Message? message)
        {
            if (message == null) return;
            await Clipboard.Default.SetTextAsync(message.Content);
            IsMenuVisible = false;
            SelectedMessage = null;
        }

        private void RefreshCommands()
        {
            SendMessageCommand.NotifyCanExecuteChanged();
            AddMemberCommand.NotifyCanExecuteChanged();
            RemoveMemberCommand.NotifyCanExecuteChanged();
            EditMessageCommand.NotifyCanExecuteChanged();
            DeleteMessageCommand.NotifyCanExecuteChanged();
            LoadMessagesCommand.NotifyCanExecuteChanged();
            LoadMoreMessagesCommand.NotifyCanExecuteChanged();
        }
    }
}