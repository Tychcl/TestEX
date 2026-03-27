using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace nearby.Classes
{
    public class ApiResponse<T>
    {
        public bool? result { get; set; }
        public string? message { get; set; }
        public T? Object { get; set; }

        public ApiResponse() { }

        public ApiResponse(bool? result, string? message, T? _object)
        {
            this.result = result;
            this.message = message;
            Object = _object;
        }
    }
}
