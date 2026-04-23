using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using nearby.Models;

namespace nearby.Classes.VM.Messanger
{
    public class UserUpdatedMessage
    {
        public User user {  get; set; }
        public UserUpdatedMessage(User u) 
        {
            user = u;
        }
    }
}
