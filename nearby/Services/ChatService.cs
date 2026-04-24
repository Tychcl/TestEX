using System.Text;
using Newtonsoft.Json;
using nearby.Classes;
using nearby.Interfaces;
using nearby.Models;

namespace nearby.Services;

public class ChatService : IChatService
{
    private readonly ApiClient _apiClient;

    public ChatService(ApiClient apiClient)
    {
        _apiClient = apiClient;
    }

    public async Task<ApiResponse<List<Chat>>> GetChatsAsync(int page = 1, int limit = 20)
    {
        var response = await _apiClient.GetAsync($"chats?page={page}&limit={limit}");
        if (response is null)
        {
            throw new Exception("Ошибка подключения к серверу");
        }
        var json = await response.Content.ReadAsStringAsync();
        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(json);
        }
        var result = JsonConvert.DeserializeObject<ApiResponse<List<Chat>>>(json);
        result.result = true;
        return result;
    }

    public async Task<ApiResponse<DetailChatInfo>> GetChatByIdAsync(int chatId, int page = 1, int limit = 50)
    {
        var response = await _apiClient.GetAsync($"chats/{chatId}?page={page}&limit={limit}");
        if (response is null)
        {
            throw new Exception("Ошибка подключения к серверу");
        }
        var json = await response.Content.ReadAsStringAsync();
        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(json);
        }
        var chatDetail = JsonConvert.DeserializeObject<DetailChatInfo>(json);
        return new ApiResponse<DetailChatInfo>(true, "", chatDetail);
    }

    public async Task<ApiResponse<int>> CreateChatAsync(string type, string? name, List<int> userIds)
    {
        var payload = new
        {
            type,
            name,
            user_ids = userIds
        };
        var json = JsonConvert.SerializeObject(payload);
        var content = new StringContent(json, Encoding.UTF8, "application/json");
        var response = await _apiClient.PostAsync("chats", content);
        if (response is null)
        {
            throw new Exception("Ошибка подключения к серверу");
        }
        json = await response.Content.ReadAsStringAsync();
        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(json);
        }
        var data = JsonConvert.DeserializeObject<Dictionary<string, object>>(json);
        if (data != null && data.TryGetValue("id", out var idObj) && idObj is long idLong)
        {
            return new ApiResponse<int>(true, "", (int)idLong);
        }
        throw new Exception("Не известная ошибка");
    }

    public async Task<ApiResponse<bool>> AddMemberAsync(int chatId, int userId)
    {
        var payload = new { user_id = userId };
        var json = JsonConvert.SerializeObject(payload);
        var content = new StringContent(json, Encoding.UTF8, "application/json");
        var response = await _apiClient.PostAsync($"chats/{chatId}/members", content);
        if (response is null)
        {
            throw new Exception("Ошибка подключения к серверу");
        }
        json = await response.Content.ReadAsStringAsync();
        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(json);
        }
        return new ApiResponse<bool>(response.IsSuccessStatusCode, json, response.IsSuccessStatusCode);
    }

    public async Task<ApiResponse<bool>> RemoveMemberAsync(int chatId, int userId)
    {
        var response = await _apiClient.DeleteAsync($"chats/{chatId}/members/{userId}");
        if (response is null)
        {
            throw new Exception("Ошибка подключения к серверу");
        }
        var json = await response.Content.ReadAsStringAsync();
        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(json);
        }
        return new ApiResponse<bool>(response.IsSuccessStatusCode, json, response.IsSuccessStatusCode);
    }

    public async Task<ApiResponse<Message>> SendMessageAsync(int chatId, MessageSendModel message)
    {
        var json = JsonConvert.SerializeObject(message);
        var content = new StringContent(json, Encoding.UTF8, "application/json");
        var response = await _apiClient.PostAsync($"chats/{chatId}/messages", content);
        if (response is null)
        {
            throw new Exception("Ошибка подключения к серверу");
        }
        json = await response.Content.ReadAsStringAsync();
        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(json);
        }
        var data = JsonConvert.DeserializeObject<Dictionary<string, object>>(json);
        if (data != null && data.TryGetValue("id", out var idObj))
        {
            // Можно получить только id, но для полного сообщения нужно сделать дополнительный запрос.
            // Однако API возвращает только id, поэтому возвращаем сообщение с минимальной информацией.
            var msg = new Message
            {
                Id = Convert.ToInt32(idObj),
                Content = message.content ?? "",
                ContentType = message.content_type,
                CreatedAt = DateTime.UtcNow,
                SenderId = 0, // Неизвестно, но можно получить из сессии позже
                SenderName = "",
                FileUrl = message.file_url ?? "",
                TranscribedText = message.transcribed_text ?? ""
            };
            return new ApiResponse<Message>(true, "", msg);
        }
        throw new Exception("Не известная ошибка");
    }

    public async Task<ApiResponse<nearby.Models.Messages>> GetMessagesAsync(int chatId, int page = 1, int limit = 50)
    {
        var response = await _apiClient.GetAsync($"chats/{chatId}/messages?page={page}&limit={limit}");
        if (response is null)
        {
            throw new Exception("Ошибка подключения к серверу");
        }
        var json = await response.Content.ReadAsStringAsync();
        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(json);
        }
        var messages = JsonConvert.DeserializeObject<nearby.Models.Messages>(json);
        return new ApiResponse<nearby.Models.Messages>(true, "", messages);
    }

    public async Task<ApiResponse<bool>> MarkMessagesAsReadAsync(int chatId, int messageId)
    {
        var response = await _apiClient.PutAsync($"chats/{chatId}/read?message_id={messageId}", null);
        if (response is null)
        {
            throw new Exception("Ошибка подключения к серверу");
        }
        var json = await response.Content.ReadAsStringAsync();
        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(json);
        }
        return new ApiResponse<bool>(response.IsSuccessStatusCode, json, response.IsSuccessStatusCode);
    }

    public async Task<ApiResponse<Message>> EditMessageAsync(int messageId, string newContent)
    {
        var payload = new { content = newContent };
        var json = JsonConvert.SerializeObject(payload);
        var content = new StringContent(json, Encoding.UTF8, "application/json");
        var response = await _apiClient.PutAsync($"messages/{messageId}", content);
        if (response is null)
        {
            throw new Exception("Ошибка подключения к серверу");
        }
        json = await response.Content.ReadAsStringAsync();
        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(json);
        }
        var msg = new Message
        {
            Id = messageId,
            Content = newContent
        };
        return new ApiResponse<Message>(true, "", msg);
    }

    public async Task<ApiResponse<bool>> DeleteMessageAsync(int messageId)
    {
        var response = await _apiClient.DeleteAsync($"messages/{messageId}");
        if (response is null)
        {
            throw new Exception("Ошибка подключения к серверу");
        }
        var json = await response.Content.ReadAsStringAsync();
        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(json);
        }
        return new ApiResponse<bool>(response.IsSuccessStatusCode, json, response.IsSuccessStatusCode);
    }
}