using System.Collections.ObjectModel;
using System.Threading.Tasks;
using CommunityToolkit.Maui;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using nearby.Interfaces;
using nearby.ContentViews.Elements;
using nearby.Models;
using nearby.Services;
using nearby.Classes;
using nearby.Views.Main;
using CommunityToolkit.Maui.Extensions;
using System.Diagnostics;

namespace nearby.ViewModels
{
    [QueryProperty(nameof(Task), "task")]
    public partial class TaskDetailViewModel : BaseViewModel
    {
        private readonly ITaskService _taskService;
        private readonly IUserService _userService;

        [ObservableProperty]
        private ObservableCollection<PopupItem> _popupItems = new();

        [ObservableProperty]
        private User _creator = null!;

        [ObservableProperty]
        private TaskItem _task = null!;

        [ObservableProperty]
        private bool _isOwner;

        [ObservableProperty]
        private bool _hasVolunteered;

        [ObservableProperty]
        private bool _canVolunteer;

        [ObservableProperty]
        private string _volunteerStatus = string.Empty;

        [ObservableProperty]
        private ObservableCollection<TaskVolunteerInfo> _volunteers = new();

        [ObservableProperty]
        private bool _inProgress;

        [ObservableProperty]
        private bool _isSearching;

        private bool _isInitialized;

        private PopupMenu popupMenu;
        public TaskDetailViewModel(ITaskService taskService, IUserService userService)
        {
            _taskService = taskService;
            _userService = userService;

            PopupItems.Add(new((string)ResourceManager.Get("EditBox"), "Редактировать", EditCommand));
            PopupItems.Add(new((string)ResourceManager.Get("Delete"), "Удалить", DeleteCommand));

            popupMenu = PopupManager.Create(PopupItems, new Thickness(0, 15, 15, 0));
        }

        partial void OnTaskChanged(TaskItem? value)
        {
            if (value == null) return;
            _ = InitializeAsync(value);
            PageTitle = value.Title;
            IsSearching = value.Status == "searching";
            InProgress = value.Status == "in_progress";
        }

        partial void OnIsOwnerChanged(bool value) => RefreshCommands();

        private async Task InitializeAsync(TaskItem task)
        {
            if (_isInitialized) return;
            IsBusy = true;
            try
            {
                IsOwner = _userService.CurrentUser?.Id == Task.CreatorId;
                if (IsOwner)
                {
                    CanVolunteer = false;
                    await LoadVolunteersAsync();
                }
                else
                {
                    await LoadMyVolunteerStatusAsync(Task.Id);
                }
                Creator = (await _userService.LoadUserByIdAsync(Task.CreatorId)).Data;
                _isInitialized = true;
            }
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
                await GoBackCommand.ExecuteAsync(null);
            }
            finally
            {
                IsBusy = false;
            }
        }

        private async Task LoadVolunteersAsync()
        {
            try
            {
                var response = await _taskService.GetTaskVolunteersAsync(Task.Id);
                Volunteers.Clear();
                if (response.Data != null)
                {
                    foreach (var v in response.Data)
                        Volunteers.Add(v);
                }
            }
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }
        }

        private async Task LoadMyVolunteerStatusAsync(int taskId)
        {
            try
            {
                var response = await _taskService.GetMyVolunteerStatusAsync(taskId);
                (CanVolunteer, HasVolunteered, VolunteerStatus) = response.Data switch
                {
                    "pending" => (false, true, "Ожидание ответа"),
                    "accepted" => (false, true, "Вас приняли"),
                    "rejected" => (false, true, "Вам отказали"),
                    "cancelled" => (false, true, "Отменено"),
                    "completed" => (false, true, "Задача завершена"),
                    _ => (true, false, "")
                };
            }
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }
            
        }

        [RelayCommand(CanExecute = nameof(CanVolunteerExecute))]
        private async Task VolunteerAsync()
        {
            try
            {
                var response = await _taskService.VolunteerForTaskAsync(Task.Id);
                CanVolunteer = false;
                HasVolunteered = true;
                VolunteerStatus = "Ожидание ответа";
                await ShowMsgAsync("Успех", "Вы откликнулись на задачу", "OK");
            }
            catch(Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }
        }
        private bool CanVolunteerExecute() => CanVolunteer && !IsBusy;

        [RelayCommand(CanExecute = nameof(CanAcceptRejectExecute))]
        private async Task AcceptVolunteerAsync(int volunteerId)
        {
            try
            {
                var response = await _taskService.AcceptVolunteerAsync(Task.Id, volunteerId);
                await LoadVolunteersAsync();
            }
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }
        }

        [RelayCommand(CanExecute = nameof(CanAcceptRejectExecute))]
        private async Task RejectVolunteerAsync(int volunteerId)
        {
            try
            {
                var response = await _taskService.RejectVolunteerAsync(Task.Id, volunteerId);
                await LoadVolunteersAsync();
            }
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }
}
        private bool CanAcceptRejectExecute() => IsOwner && !IsBusy;

        [RelayCommand(CanExecute = nameof(CanStartTaskExecute))]
        private async Task StartTaskAsync()
        {
            try
            {
                var response = await _taskService.StartTaskAsync(Task.Id);
                Task.Status = "in_progress";
                await ShowMsgAsync("Успех", "Задача начата", "OK");
            }
            catch (Exception e)
            {
                await ShowErrorAsync(e.Message);
            }
        }
        private bool CanStartTaskExecute() => IsOwner && Task?.Status == "searching" && !IsBusy;

        [RelayCommand(CanExecute = nameof(CanCompleteTaskExecute))]
        private async Task CompleteTaskAsync()
        {
            try
            {
                var confirm = await Application.Current!.MainPage!.DisplayAlert(
                "Завершение", "Вы уверены, что задача выполнена?", "Да", "Нет");
                if (!confirm) return;
                var response = await _taskService.CompleteTaskAsync(Task.Id);
                Task.Status = "completed";
                await ShowMsgAsync("Успех", "Задача завершена, награда начислена", "OK");
            }
            catch (Exception ex)
            {
                await ShowErrorAsync(ex.Message);
            }
        }
        private bool CanCompleteTaskExecute() => IsOwner && Task?.Status == "in_progress" && !IsBusy;

        [RelayCommand]
        private async Task EditAsync()
        {
            await Shell.Current.GoToAsync(nameof(TaskAddEditPage), new Dictionary<string, object?> { { "task", _task } });
        }

        [RelayCommand(CanExecute = nameof(CanDeleteTaskExecute))]
        private async Task DeleteAsync()
        {
            var confirm = await Application.Current!.MainPage!.DisplayAlert(
                "Удаление", "Удалить задачу?", "Да", "Нет");
            if (!confirm) return;

            var response = await _taskService.DeleteTaskAsync(Task.Id);

            await ShowMsgAsync("Успех", "Задача удалена", "OK");
            await GoBackCommand.ExecuteAsync(null);
        }
        private bool CanDeleteTaskExecute() => IsOwner && !IsBusy;

        [RelayCommand]
        private async Task GoToProfileAsync(int userId)
        {
            await Shell.Current.GoToAsync(nameof(ProfilePage), new Dictionary<string, object?> { { "id", userId } });
        }


        [RelayCommand]
        private async Task OpenPopupMenuAsync()
        {
            await PopupManager.Show(popupMenu);
        }

        private void RefreshCommands()
        {
            StartTaskCommand.NotifyCanExecuteChanged();
            CompleteTaskCommand.NotifyCanExecuteChanged();
            AcceptVolunteerCommand.NotifyCanExecuteChanged();
            RejectVolunteerCommand.NotifyCanExecuteChanged();
            DeleteCommand.NotifyCanExecuteChanged();
            VolunteerCommand.NotifyCanExecuteChanged();
        }
        protected override void OnBusyStateChanged(bool isBusy)
        {
            base.OnBusyStateChanged(isBusy);
            RefreshCommands();
        }
    }
}