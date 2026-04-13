    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text;
    using System.Threading.Tasks;
    using nearby.Classes;

namespace nearby.Classes.Interface.Behaviors
{
    public class EmailBehavior : ValidationBehavior
    {
        public EmailBehavior()
        {
            ValidateFunc = Validate.EmailAdress;
        }
    }
}
