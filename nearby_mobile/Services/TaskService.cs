using System.Text;
using Newtonsoft.Json;
using nearby_mobile.Interfaces;
using nearby_mobile.Models;

namespace nearby_mobile.Services;
public class ApiResponse<T>
{
    public List<T> Data { get; set; } = new();
    public int Total { get; set; }
    public int Page { get; set; }
    public int Limit { get; set; }
    public int Pages { get; set; }
}

public class TaskService : ITaskService
{
    private readonly ApiClient _apiClient;

    public TaskService(ApiClient apiClient)
    {
        _apiClient = apiClient;
    }

    public async Task<List<TaskItem>> GetTasksAsync(int page = 1, int limit = 10, string status = null, string priority = null, string city = null)
    {
        var queryParams = new List<string>();
        queryParams.Add($"page={page}");
        queryParams.Add($"limit={limit}");
        if (!string.IsNullOrEmpty(status)) queryParams.Add($"status={status}");
        if (!string.IsNullOrEmpty(priority)) queryParams.Add($"priority={priority}");
        if (!string.IsNullOrEmpty(city)) queryParams.Add($"city={Uri.EscapeDataString(city)}");

        var url = $"tasks?{string.Join("&", queryParams)}";
        var response = await _apiClient.GetAsync(url);

        if (response.IsSuccessStatusCode)
        {
            var json = await response.Content.ReadAsStringAsync();
            var result = JsonConvert.DeserializeObject<ApiResponse<TaskItem>>(json);
            return result?.Data ?? new List<TaskItem>();
        }
        return new List<TaskItem>();
    }

    public async Task<TaskItem> GetTaskAsync(int id)
    {
        var response = await _apiClient.GetAsync($"tasks/{id}");
        if (response.IsSuccessStatusCode)
        {
            var json = await response.Content.ReadAsStringAsync();
            return JsonConvert.DeserializeObject<TaskItem>(json);
        }
        return null;
    }

    public async Task<bool> CreateTaskAsync(TaskItem task)
    {
        var json = JsonConvert.SerializeObject(task);
        var content = new StringContent(json, Encoding.UTF8, "application/json");
        var response = await _apiClient.PostAsync("tasks", content);
        return response.IsSuccessStatusCode;
    }

    public async Task<bool> UpdateTaskAsync(int id, TaskItem task)
    {
        var json = JsonConvert.SerializeObject(task);
        var content = new StringContent(json, Encoding.UTF8, "application/json");
        var response = await _apiClient.PutAsync($"tasks/{id}", content);
        return response.IsSuccessStatusCode;
    }

    public async Task<bool> DeleteTaskAsync(int id)
    {
        var response = await _apiClient.DeleteAsync($"tasks/{id}");
        return response.IsSuccessStatusCode;
    }
}