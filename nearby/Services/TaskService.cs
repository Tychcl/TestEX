using System.Text;
using Newtonsoft.Json;
using nearby.Interfaces;
using nearby.Models;
using nearby.Classes;

namespace nearby.Services;

public class TaskService : ITaskService
{
    private readonly ApiClient _apiClient;

    public TaskService(ApiClient apiClient)
    {
        _apiClient = apiClient;
    }

    public async Task<ApiResponse<List<TaskItem>>> GetUserTasksAsync(int userId, string status, int page = 1, int pageSize = 10)
    {
        var response = await _apiClient.GetAsync($"tasks/user/{userId}?status={status}&page={page}&limit={pageSize}");
        if(response is null)
        {
            return new ApiResponse<List<TaskItem>>(null, "Ошибка подключения к серверу", null);
        }
        var json = await response.Content.ReadAsStringAsync();
        if (response.IsSuccessStatusCode)
        {
            ApiResponse<List<TaskItem>> r = JsonConvert.DeserializeObject<ApiResponse<List<TaskItem>>>(json) ?? new();
            r.result = true;
            return r;
        }
        return new ApiResponse<List<TaskItem>>(false, json, null);
    }

    public async Task<ApiResponse<List<TaskItem>>> GetTasksAsync(int page = 1, int limit = 10, string status = null, string priority = null, string city = null)
    {
        var queryParams = new List<string>();
        queryParams.Add($"page={page}");
        queryParams.Add($"limit={limit}");
        if (!string.IsNullOrEmpty(status)) queryParams.Add($"status={status}");
        if (!string.IsNullOrEmpty(priority)) queryParams.Add($"priority={priority}");
        if (!string.IsNullOrEmpty(city)) queryParams.Add($"city={Uri.EscapeDataString(city)}");

        var url = $"tasks?{string.Join("&", queryParams)}";
        var response = await _apiClient.GetAsync(url);
        if (response is null)
        {
            return new ApiResponse<List<TaskItem>>(null, "Ошибка подключения к серверу", null);
        }
        var json = await response.Content.ReadAsStringAsync();
        if (response.IsSuccessStatusCode)
        {
            var result = JsonConvert.DeserializeObject<ApiResponse<List<TaskItem>>>(json);
            result.result = true;
            return result;
        }
        return new ApiResponse<List<TaskItem>>(false, json, null);
    }

    public async Task<ApiResponse<TaskItem>> GetTaskAsync(int id)
    {
        var response = await _apiClient.GetAsync($"tasks/{id}");
        if (response is null)
        {
            return new ApiResponse<TaskItem>(null, "Ошибка подключения к серверу", null);
        }
        var json = await response.Content.ReadAsStringAsync();
        if (response.IsSuccessStatusCode)
        {
            var r = JsonConvert.DeserializeObject<TaskItem>(json);
            return new ApiResponse<TaskItem>(true, "", r);
        }
        return new ApiResponse<TaskItem>(false, json, null);
    }

    public async Task<ApiResponse<TaskItem>> CreateTaskAsync(TaskItem task)
    {
        var json = JsonConvert.SerializeObject(task);
        var content = new StringContent(json, Encoding.UTF8, "application/json");
        var response = await _apiClient.PostAsync("tasks", content);
        if (response is null)
        {
            return new ApiResponse<TaskItem>(null, "Ошибка подключения к серверу", null);
        }
        json = await response.Content.ReadAsStringAsync();
        return new ApiResponse<TaskItem>(response.IsSuccessStatusCode, json, null);
    }

    public async Task<ApiResponse<TaskItem>> UpdateTaskAsync(int id, TaskItem task)
    {
        var json = JsonConvert.SerializeObject(task);
        var content = new StringContent(json, Encoding.UTF8, "application/json");
        var response = await _apiClient.PutAsync($"tasks/{id}", content);
        if (response is null)
        {
            return new ApiResponse<TaskItem>(null, "Ошибка подключения к серверу", null);
        }
        json = await response.Content.ReadAsStringAsync();
        return new ApiResponse<TaskItem>(response.IsSuccessStatusCode, json, null);
    }

    public async Task<ApiResponse<TaskItem>> DeleteTaskAsync(int id)
    {
        var response = await _apiClient.DeleteAsync($"tasks/{id}");
        if (response is null)
        {
            return new ApiResponse<TaskItem>(null, "Ошибка подключения к серверу", null);
        }
        var json = await response.Content.ReadAsStringAsync();
        return new ApiResponse<TaskItem>(response.IsSuccessStatusCode, json, null);
    }

    public async Task<ApiResponse<TaskItem>> VolunteerForTaskAsync(int taskId)
    {
        var response = await _apiClient.PostAsync($"tasks/{taskId}/volunteer", null);
        if (response is null)
        {
            return new ApiResponse<TaskItem>(null, "Ошибка подключения к серверу", null);
        }
        var json = await response.Content.ReadAsStringAsync();
        return new ApiResponse<TaskItem>(response.IsSuccessStatusCode, json, null);
    }

    public async Task<ApiResponse<List<TaskVolunteerInfo>>> GetTaskVolunteersAsync(int taskId)
    {
        var response = await _apiClient.GetAsync($"tasks/{taskId}/volunteers");
        if (response is null)
        {
            return new ApiResponse<List<TaskVolunteerInfo>>(null, "Ошибка подключения к серверу", null);
        }
        var json = await response.Content.ReadAsStringAsync();
        if (response.IsSuccessStatusCode)
        {
            return new ApiResponse<List<TaskVolunteerInfo>>(true, "", JsonConvert.DeserializeObject<List<TaskVolunteerInfo>>(json));
        }
        return new ApiResponse<List<TaskVolunteerInfo>>(response.IsSuccessStatusCode, json, null);
    }

    public async Task<ApiResponse<TaskItem>> AcceptVolunteerAsync(int taskId, int volunteerUserId)
    {
        var response = await _apiClient.PutAsync($"tasks/{taskId}/volunteers/{volunteerUserId}/accept", null);
        if (response is null)
        {
            return new ApiResponse<TaskItem>(null, "Ошибка подключения к серверу", null);
        }
        var json = await response.Content.ReadAsStringAsync();
        return new ApiResponse<TaskItem>(response.IsSuccessStatusCode, json, null);
    }

    public async Task<ApiResponse<TaskItem>> RejectVolunteerAsync(int taskId, int volunteerUserId)
    {
        var response = await _apiClient.PutAsync($"tasks/{taskId}/volunteers/{volunteerUserId}/reject", null);
        if (response is null)
        {
            return new ApiResponse<TaskItem>(null, "Ошибка подключения к серверу", null);
        }
        var json = await response.Content.ReadAsStringAsync();
        return new ApiResponse<TaskItem>(response.IsSuccessStatusCode, json, null);
    }

    public async Task<ApiResponse<TaskItem>> StartTaskAsync(int taskId)
    {
        var response = await _apiClient.PutAsync($"tasks/{taskId}/start", null);
        if (response is null)
        {
            return new ApiResponse<TaskItem>(null, "Ошибка подключения к серверу", null);
        }
        var json = await response.Content.ReadAsStringAsync();
        return new ApiResponse<TaskItem>(response.IsSuccessStatusCode, json, null);
    }

    public async Task<ApiResponse<TaskItem>> CompleteTaskAsync(int taskId)
    {
        var response = await _apiClient.PutAsync($"tasks/{taskId}/complete", null);
        if (response is null)
        {
            return new ApiResponse<TaskItem>(null, "Ошибка подключения к серверу", null);
        }
        var json = await response.Content.ReadAsStringAsync();
        return new ApiResponse<TaskItem>(response.IsSuccessStatusCode, json, null);
    }

    public async Task<ApiResponse<string>> GetMyVolunteerStatusAsync(int taskId)
    {
        var response = await _apiClient.GetAsync($"tasks/{taskId}/my-status");
        if (response is null)
        {
            return new ApiResponse<string>(null, "Ошибка подключения к серверу", null);
        }
        var json = await response.Content.ReadAsStringAsync();
        if (response.IsSuccessStatusCode)
        {
            var data = JsonConvert.DeserializeObject<Dictionary<string, string>>(json);
            return new ApiResponse<string>(true, "", data?.GetValueOrDefault("status"));
        }
        return new ApiResponse<string>(false, json, null);
    }

}