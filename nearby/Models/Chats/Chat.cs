using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace nearby.Models;

public class Chat
{
    public int Id { get; set; }
    public string Type { get; set; }
    public string? Name { get; set; }
    public DateTime CreatedAt { get; set; }
    public User OtherUser { get; set; }
    public Message LastMessage { get; set; }
    public int UnreadCount { get; set; } = 0;
}
