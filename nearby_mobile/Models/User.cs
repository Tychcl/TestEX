using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace nearby_mobile.Models
{
    public class User
    {
        public int Id { get; set; }
        public string FullName { get; set; } = string.Empty;
        public string? Phone { get; set; }
        public string? Email { get; set; }
        public string? City { get; set; }
        public DateTime? BirthDate { get; set; }
        public string? About { get; set; }
        public string? ProfilePicture { get; set; }
        public decimal Balance { get; set; }
        public string AvailabilityStatus { get; set; } = "available";
        public bool IsAdmin { get; set; }
        public bool IsModerator { get; set; }
        public DateTime? LastSeenAt { get; set; }
        public bool IsOnline { get; set; }
        public double AverageRating { get; set; }
        public DateTime CreatedAt { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}
