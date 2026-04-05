using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace nearby.Models
{
    public class MessageSendModel
    {
        public string ContentType { get; set; } = "text";
        public string? Content { get; set; }
        public string? FileUrl { get; set; }
        public string? TranscribedText { get; set; }
    }
}
