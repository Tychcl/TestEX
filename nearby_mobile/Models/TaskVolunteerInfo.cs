using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace nearby_mobile.Models
{
    public class TaskVolunteerInfo
    {
        public int Id { get; set; }
        private string _fullname { get; set; }
        public string FullName 
        {
            get => _fullname;
            set
            {
                var parts = value.Split(' ');
                Surname = parts.Length > 0 ? parts[0] : "";
                Name = parts.Length > 1 ? parts[1] : "";
                Patronymic = parts.Length > 2 ? parts[2] : "";
            }
        }
        public string Name { get; set; } = "";
        public string Surname { get; set; } = "";
        public string Patronymic { get; set; } = "";
        public DateTime? BirthDate { get; set; } = DateTime.MinValue;
        public string ProfilePicture { get; set; }
        private string _status { get; set; }
        public string Status 
        { 
            get => _status;
            set
            {
                _status = value;
                pending = value == "pending";
                accepted = value == "accepted";
                rejected = value == "rejected";
            }
        } // pending, accepted, rejected
        public bool pending { get; set; } = false;
        public bool accepted { get; set; } = false;
        public bool rejected { get; set; } = false;
    }
}
