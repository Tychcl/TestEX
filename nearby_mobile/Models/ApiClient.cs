using System.Net.Http.Headers;
using System.Text;
using nearby_mobile.Interfaces;

public class ApiClient
{
    private readonly HttpClient _httpClient;
    private readonly ITokenService _tokenService;

    public ApiClient(HttpClient httpClient, ITokenService tokenService)
    {
        _httpClient = httpClient;
        _httpClient.Timeout = TimeSpan.FromSeconds(20);
        _httpClient.BaseAddress = new Uri("http://10.0.2.2:8080/api/");
        _tokenService = tokenService;
    }

    private async Task<HttpRequestMessage> CreateRequestAsync(HttpMethod method, string url, HttpContent? content = null)
    {
        var request = new HttpRequestMessage(method, url)
        {
            Content = content
        };

        var token = await _tokenService.GetTokenAsync();
        if (!string.IsNullOrEmpty(token))
        {
            request.Headers.Add("Cookie", $"jwt={token}");
        }

        return request;
    }

    public async Task<HttpResponseMessage?> GetAsync(string url)
    {
        try
        {
            var request = await CreateRequestAsync(HttpMethod.Get, url);
            return await _httpClient.SendAsync(request);
        }
        catch
        {
            return null;
        }
        
    }

    public async Task<HttpResponseMessage?> PostAsync(string url, HttpContent content)
    {
        try
        {
            var request = await CreateRequestAsync(HttpMethod.Post, url, content);
            return await _httpClient.SendAsync(request);
        }
        catch
        {
            return null;
        }
    }

    public async Task<HttpResponseMessage?> PutAsync(string url, HttpContent content)
    {
        try
        {
            var request = await CreateRequestAsync(HttpMethod.Put, url, content);
            return await _httpClient.SendAsync(request);
        }
        catch
        {
            return null;
        }
    }

    public async Task<HttpResponseMessage?> DeleteAsync(string url)
    {
        try
        {
            var request = await CreateRequestAsync(HttpMethod.Delete, url);
            return await _httpClient.SendAsync(request);
        }
        catch
        {
            return null;
        }
    }
}