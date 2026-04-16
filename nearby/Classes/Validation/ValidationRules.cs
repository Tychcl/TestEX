using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace nearby.Classes.Validation
{
    public static class ValidationRules
    {
        public static ValidationResult ValidateLogin(string login, ValidationContext context)
        {
            if (string.IsNullOrWhiteSpace(login))
                return new ValidationResult("Логин не может быть пустым.");

            bool isValid = Validate.EmailOrPhone(login);

            return isValid ? ValidationResult.Success : new ValidationResult("Логин должен быть в формате +79999999999 или example@mail.com");
        }
    }
}
