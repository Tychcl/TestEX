using nearby.Views.Additional;

namespace nearby.Views.Main;
public partial class MainShell : Shell
{
    public MainShell()
    {
        InitializeComponent();
        Routing.RegisterRoute(nameof(ProfilePage), typeof(ProfilePage));
        Routing.RegisterRoute(nameof(EditProfilePage), typeof(EditProfilePage));
        Routing.RegisterRoute(nameof(TaskDetailPage), typeof(TaskDetailPage));
        Routing.RegisterRoute(nameof(ChatDetailPage), typeof(ChatDetailPage));
    }
}
