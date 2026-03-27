using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Text;
using nearby_mobile.Interfaces;
using nearby_mobile.Models;
using Newtonsoft.Json;

namespace nearby_mobile.Services;

public class UserService : IUserService
{
    private readonly ApiClient _apiClient;
    private readonly ITokenService _tokenService;
    private User? _currentUser;

    public event PropertyChangedEventHandler? PropertyChanged;

    public UserService(ApiClient apiClient, ITokenService tokenService)
    {
        _apiClient = apiClient;
        _tokenService = tokenService;
    }

    public User? CurrentUser
    {
        get => _currentUser;
        set
        {
            _currentUser = value;
            OnPropertyChanged();
        }
    }

    public async Task<User?> LoadUserByIdAsync(int id = -1)
    {
        var token = await _tokenService.GetTokenAsync();
        if (string.IsNullOrEmpty(token))
        {
            CurrentUser = null;
            return null;
        }

        var response = await _apiClient.GetAsync($"users/{id}");
        if (response is not null && response.IsSuccessStatusCode)
        {
            var json = await response.Content.ReadAsStringAsync();
            var user = JsonConvert.DeserializeObject<User>(json);
            if (id == -1 || CurrentUser?.Id == id)
            {
                CurrentUser = user;
                //PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(nameof()));
            }
            return user;
        }
        return null;
    }

    public async Task<bool> UpdateUserAsync(object updatedData)
    {
        var json = JsonConvert.SerializeObject(updatedData);
        var content = new StringContent(json, Encoding.UTF8, "application/json");
        var response = await _apiClient.PutAsync("users", content);
        if (response.IsSuccessStatusCode)
        {
            await LoadUserByIdAsync(_currentUser.Id);
            return true;
        }
        return false;
    }

    protected void OnPropertyChanged([CallerMemberName] string prop = "")
    {
        PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(prop));
    }
}