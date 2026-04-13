using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Text.RegularExpressions;
using System.Threading.Tasks;
using Microsoft.Maui.ApplicationModel.Communication;
using static Microsoft.Maui.ApplicationModel.Permissions;

namespace nearby.Classes
{
    public static class Validate
    {
        public static Func<string, bool> EmailValidator => EmailAdress;
        public static Func<string, bool> PhoneValidator => PhoneNumber;
        public static Func<string, bool> EmailOrPhoneValidator => EmailOrPhone;
        public static Func<string, bool> PasswordValidator => Password;

        public static bool EmailAdress(string str)
        {
            str = str.Trim();
            if (string.IsNullOrWhiteSpace(str)) return false;
            return Regex.IsMatch(str, @"^[^@\s]+@[^@\s]+\.[^@\s]+$", RegexOptions.IgnoreCase);
        }

        public static bool PhoneNumber(string str)
        {
            str = str.Trim();
            if (string.IsNullOrWhiteSpace(str)) return false;
            return Regex.IsMatch(str, @"^\+[0-9]{10,15}$");
        } 

        public static bool EmailOrPhone(string str)
        {
            str = str.Trim();
            if (string.IsNullOrWhiteSpace(str)) return false;
            return EmailAdress(str) || PhoneNumber(str);
        }

        public static bool Password(string str)
        {
            str = str.Trim();
            if (string.IsNullOrWhiteSpace(str)) return false;
            return Regex.IsMatch(str, "^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-._]).{8,}$");
        }
    }
}
