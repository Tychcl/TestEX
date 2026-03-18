using nearby_mobile.Views;

namespace nearby_mobile
{
    public partial class AppShell : Shell
    {
        public AppShell()
        {
            InitializeComponent();
            Routing.RegisterRoute(nameof(EditProfilePage), typeof(EditProfilePage));
            Routing.RegisterRoute("profile", typeof(ProfilePage));
        }
    }
}
