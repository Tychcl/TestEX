using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Text.Json;
using System.Text.Json.Serialization;
using nearby_mobile.Interfaces;
using nearby_mobile.Models;

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
            if (_currentUser != value)
            {
                _currentUser = value;
                OnPropertyChanged();
            }
        }
    }

    public async Task LoadUserAsync()
    {
        var token = await _tokenService.GetTokenAsync();
        if (string.IsNullOrEmpty(token))
        {
            CurrentUser = null;
            return;
        }

        var response = await _apiClient.GetAsync("users/me");
        if (response.IsSuccessStatusCode)
        {
            var json = await response.Content.ReadAsStringAsync();
            var options = new JsonSerializerOptions
            {
                PropertyNameCaseInsensitive = true,
                NumberHandling = JsonNumberHandling.AllowReadingFromString
            };
            CurrentUser = JsonSerializer.Deserialize<User>(json, options);
        }
    }

    protected void OnPropertyChanged([CallerMemberName] string prop = "")
    {
        PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(prop));
    }
}