using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace nearby.Classes
{
    public static class ResourceManager
    {
        public static ICollection<ResourceDictionary> MergedDictionaries => Application.Current.Resources.MergedDictionaries;
        public static object Get(string key)
        {
            return Application.Current.Resources[key];
        }
    }
}
