using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace nearby_mobile.Interfaces
{
    public interface IAuthService
    {
        Task<bool?> LoginAsync(string login, string password);
        Task<bool> RegisterAsync(string fullName, string phone, string email, string password);
        Task LogoutAsync();
        Task<string?> GetTokenAsync();
    }
}
