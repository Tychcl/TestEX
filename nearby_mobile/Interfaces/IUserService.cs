using System.ComponentModel;
using nearby_mobile.Models;

namespace nearby_mobile.Services;

public interface IUserService : INotifyPropertyChanged
{
    User? CurrentUser { get; set; }
    Task<User?> LoadUserByIdAsync(int id = -1);
    Task<bool> UpdateUserAsync(object updatedData);
}