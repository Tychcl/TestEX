using System.Collections.ObjectModel;
using System.Windows.Input;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using nearby.Classes.VM;
using nearby.Interfaces;
using nearby.Models;
using nearby.Views.Additional;

namespace nearby.ViewModels
{
    public partial class ChatsViewModel : BaseViewModel2
    {
        private readonly IChatService _chatService;
        private readonly IServiceProvider _serviceProvider;

        [ObservableProperty]
        private ObservableCollection<Chat> _chats = new();

        [ObservableProperty]
        private bool _isRefreshing;
        partial void OnIsRefreshingChanged(bool value)
        {
            LoadMoreCommand.NotifyCanExecuteChanged();
        }

        protected override void OnBusyStateChanged(bool isBusy)
        {
            base.OnBusyStateChanged(isBusy);
            RefreshCommand.NotifyCanExecuteChanged();
            LoadMoreCommand.NotifyCanExecuteChanged();
        }

        private int _currentPage = 1;
        private bool _hasMorePages = true;
        private const int PageSize = 20;

        public ChatsViewModel(IChatService chatService, IServiceProvider serviceProvider)
        {
            _chatService = chatService;
            _serviceProvider = serviceProvider;
        }

        [RelayCommand]
        private async Task LoadChatsAsync() => await LoadChatsBaseAsync(true);
        [RelayCommand(CanExecute = nameof(CanRefresh))]
        private async Task RefreshAsync() => await LoadChatsBaseAsync(true);
        [RelayCommand(CanExecute = nameof(CanLoadMore))]
        private async Task LoadMoreAsync() => await LoadChatsBaseAsync(false);
        private bool CanRefresh() => !IsBusy;
        private bool CanLoadMore() => !IsBusy && _hasMorePages;

        private async Task LoadChatsBaseAsync(bool reset)
        {
            try
            {
                if (IsBusy) return;
                IsBusy = true;
                if (reset)
                {
                    _currentPage = 1;
                    _hasMorePages = true;
                    Chats.Clear();
                }

                if (!_hasMorePages) return;

                IsRefreshing = true;

                var response = await _chatService.GetChatsAsync(_currentPage, PageSize);
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
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }
            finally
            {
                IsBusy = false;
                IsRefreshing = false;
            }
        }

        [RelayCommand]
        private async Task GoToChatDetailAsync(Chat chat)
        {
            await Shell.Current.GoToAsync(nameof(ChatDetailPage), new Dictionary<string, object?> { { "id", chat.Id } });
        }

        [RelayCommand]
        private async Task CreateGroupAsync()
        {
            await Application.Current.MainPage.DisplayAlert("Создание группы", "Функция в разработке", "OK");
        }
    }
}