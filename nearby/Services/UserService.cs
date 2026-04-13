using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Text;
using nearby.Interfaces;
using nearby.Models;
using nearby.Classes;
using Newtonsoft.Json;

namespace nearby.Services;

public class UserService : NotifyPropertyChanged, IUserService
{
    private readonly ApiClient _apiClient;
    private readonly ITokenService _tokenService;
    private User? _currentUser;

    public UserService(ApiClient apiClient, ITokenService tokenService)
    {
        _apiClient = apiClient;
        _tokenService = tokenService;
    }

    public User? CurrentUser
    {
        get => _currentUser;
        set => SetField(ref _currentUser, value);
    }

    public async Task<ApiResponse<User>> LoadUserByIdAsync(int id = -1)
    {
        var token = await _tokenService.GetTokenAsync();
        if (string.IsNullOrEmpty(token))
        {
            CurrentUser = null;
            return new ApiResponse<User>(null, "Неавторизован", null); ;
        }

        var response = await _apiClient.GetAsync($"users/{id}");

        if (response is null)
        {
            return new ApiResponse<User>(null, "Неудается подключиться к серверу", null);
        }
        var json = await response.Content.ReadAsStringAsync();
        if (response.IsSuccessStatusCode)
        {
            var user = JsonConvert.DeserializeObject<User>(json);
            if (id == -1 || CurrentUser?.Id == id)
            {
                CurrentUser = user;
                //PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(nameof()));
            }
            return new ApiResponse<User>(true, "Данные получены", user);
        }
        return new ApiResponse<User>(false, json, null);
    }

    public async Task<ApiResponse<User>> UpdateUserByIdAsync(object updatedData, int id = -1)
    {
        var json = JsonConvert.SerializeObject(updatedData);
        var content = new StringContent(json, Encoding.UTF8, "application/json");
        var response = await _apiClient.PutAsync($"users/{id}", content);

        if (response is null)
        {
            return new ApiResponse<User>(null, "Неудается подключиться к серверу", null);
        }
        json = await response.Content.ReadAsStringAsync();
        if (response.IsSuccessStatusCode)
        {
            var user = await LoadUserByIdAsync(_currentUser.Id);
            this.OnPropertyChanged(nameof(IUserService.CurrentUser));
            return new ApiResponse<User>(true, "Данные обновленны", user.Data);
        }
        return new ApiResponse<User>(false, json, null); ;
    }
}