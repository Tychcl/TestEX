using System.Text;
using nearby.Interfaces;
using nearby.Classes;
using nearby.Models;
using Newtonsoft.Json;

//signin, signup, signout
public class AuthService : IAuthService
{
    private readonly ApiClient _apiClient;
    private readonly ITokenService _tokenService;

    public AuthService(ApiClient apiClient, ITokenService tokenService)
    {
        _apiClient = apiClient;
        _tokenService = tokenService;
    }

    public async Task<ApiResponse<User>> LoginAsync(string login, string password)
    {
        var request = new { login, password };
        var content = new StringContent(JsonConvert.SerializeObject(request), Encoding.UTF8, "application/json");
        var response = await _apiClient.PostAsync("users/signin", content);
        //var j = await response.Content.ReadAsStringAsync();

        if (response is null)
        {
            return new ApiResponse<User>(null, "Неудается подключиться к серверу", null);
        }

        if (response.IsSuccessStatusCode)
        {
            if (response.Headers.TryGetValues("Set-Cookie", out var cookieValues))
            {
                var jwtCookie = cookieValues.FirstOrDefault(c => c.StartsWith("jwt="));
                if (jwtCookie != null)
                {
                    var token = jwtCookie.Substring("jwt=".Length).Split(';').FirstOrDefault();
                    await _tokenService.SetTokenAsync(token);
                    var json = await response.Content.ReadAsStringAsync();
                    var user = JsonConvert.DeserializeObject<User>(json);
                    return new ApiResponse<User>(true, "", user);
                }
            }
            return new ApiResponse<User>(false, "Успешный ответ, но кука jwt отсутствует", null);
            //System.Diagnostics.Debug.WriteLine();
        }
        return new ApiResponse<User>(false, "Неверный логин или пароль", null);
    }

    public async Task<ApiResponse<bool?>> RegisterAsync(string fullName, string phone, string email, string password)
    {
        var request = new { full_name = fullName, phone, email, password, confirm = password };
        var content = new StringContent(JsonConvert.SerializeObject(request), Encoding.UTF8, "application/json");
        var response = await _apiClient.PostAsync("users/register", content);
        var json = await response.Content.ReadAsStringAsync();
        return new ApiResponse<bool?>(response?.IsSuccessStatusCode, json, null);
    }

    public async Task LogoutAsync()
    {
        await _tokenService.ClearTokenAsync();
    }

    public async Task<string?> GetTokenAsync() => await _tokenService.GetTokenAsync();
}