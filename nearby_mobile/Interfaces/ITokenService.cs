using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace nearby_mobile.Interfaces
{
    public interface ITokenService
    {
        Task<string?> GetTokenAsync();
        Task SetTokenAsync(string token);
        Task ClearTokenAsync();
    }
}
