using System.Text;
using nearby_mobile.Interfaces;
using Newtonsoft.Json;

public class AuthService : IAuthService
{
    private readonly ApiClient _apiClient;
    private readonly ITokenService _tokenService;

    public AuthService(ApiClient apiClient, ITokenService tokenService)
    {
        _apiClient = apiClient;
        _tokenService = tokenService;
    }

    public async Task<bool> LoginAsync(string login, string password)
    {
        var request = new { login, password };
        var content = new StringContent(JsonConvert.SerializeObject(request), Encoding.UTF8, "application/json");
        var response = await _apiClient.PostAsync("users/signin", content);

        if (response.IsSuccessStatusCode)
        {
            if (response.Headers.TryGetValues("Set-Cookie", out var cookieValues))
            {
                var jwtCookie = cookieValues.FirstOrDefault(c => c.StartsWith("jwt="));
                if (jwtCookie != null)
                {
                    var token = jwtCookie.Substring("jwt=".Length).Split(';').FirstOrDefault();
                    await _tokenService.SetTokenAsync(token);
                    return true;
                }
            }
            System.Diagnostics.Debug.WriteLine("Успешный ответ, но кука jwt отсутствует");
        }
        return false;
    }

    public async Task<bool> RegisterAsync(string fullName, string phone, string email, string password)
    {
        var request = new { full_name = fullName, phone, email, password, confirm = password };
        var content = new StringContent(JsonConvert.SerializeObject(request), Encoding.UTF8, "application/json");
        var response = await _apiClient.PostAsync("users/register", content);
        return response.IsSuccessStatusCode;
    }

    public async Task LogoutAsync()
    {
        await _tokenService.ClearTokenAsync();
    }

    public async Task<string?> GetTokenAsync() => await _tokenService.GetTokenAsync();
}