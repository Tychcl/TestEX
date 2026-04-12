using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Microsoft.Maui.ApplicationModel.Communication;
using System.Text.RegularExpressions;

namespace nearby.Classes
{
    public static class Validate
    {
        public static bool EmailAdress(string email)
        {
            if (string.IsNullOrEmpty(email)) return false;
            var addr = new System.Net.Mail.MailAddress(email);
            return addr.Address == email;
        }

        public static bool PhoneNumber(string phone)
        {
            if (string.IsNullOrEmpty(phone)) return false;
            return Regex.IsMatch(phone, "+[0-9]{11}");
        }

        public static bool Password(string password)
        {
            if (string.IsNullOrEmpty(password)) return false;
            return Regex.IsMatch(password, @"^(?=.*[а-я])(?=.*[А-Я])(?=.*[a-z])(?=.*[A-Z])(?=.*[^\da-zA-Z]).{8,}$");
        }
    }
}
