using System.ComponentModel;
using nearby_mobile.Models;

namespace nearby_mobile.Services;

public interface IUserService : INotifyPropertyChanged
{
    User? CurrentUser { get; set; }
    Task LoadUserAsync();
}