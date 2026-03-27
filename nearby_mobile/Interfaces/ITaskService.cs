using nearby_mobile.Models;

namespace nearby_mobile.Interfaces;

public interface ITaskService
{
    Task<List<TaskItem>> GetTasksAsync(int page = 1, int limit = 10, string status = null, string priority = null, string city = null);
    Task<List<TaskItem>> GetUserTasksAsync(int userId, string status, int page = 1, int pageSize = 10);
    Task<TaskItem> GetTaskAsync(int id);
    Task<bool> CreateTaskAsync(TaskItem task);
    Task<bool> UpdateTaskAsync(int id, TaskItem task);
    Task<bool> DeleteTaskAsync(int id);

    Task<bool> VolunteerForTaskAsync(int taskId);
    Task<List<TaskVolunteerInfo>> GetTaskVolunteersAsync(int taskId);
    Task<bool> AcceptVolunteerAsync(int taskId, int volunteerUserId);
    Task<bool> RejectVolunteerAsync(int taskId, int volunteerUserId);
    Task<bool> StartTaskAsync(int taskId);
    Task<bool> CompleteTaskAsync(int taskId);
    Task<string> GetMyVolunteerStatusAsync(int taskId);
}