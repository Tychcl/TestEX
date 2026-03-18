using nearby_mobile.Models;

namespace nearby_mobile.Interfaces;

public interface ITaskService
{
    Task<List<TaskItem>> GetTasksAsync(int page = 1, int limit = 10, string status = null, string priority = null, string city = null);
    Task<TaskItem> GetTaskAsync(int id);
    Task<bool> CreateTaskAsync(TaskItem task);
    Task<bool> UpdateTaskAsync(int id, TaskItem task);
    Task<bool> DeleteTaskAsync(int id);
}