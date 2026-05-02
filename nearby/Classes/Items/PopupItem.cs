using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Input;
using CommunityToolkit.Mvvm.Input;

namespace nearby.Classes
{
    public class PopupItem : BaseItem
    {
        public ICommand Command;

        public PopupItem() { }

        public PopupItem(string icon, string text, ICommand command) : base(icon, text) { Command = command; }
    }
}
