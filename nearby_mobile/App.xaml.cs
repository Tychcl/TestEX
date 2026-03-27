using nearby_mobile.Interfaces;
using nearby_mobile.Services;
using nearby_mobile.Views;

namespace nearby_mobile
{
    public partial class App : Application
    {
        private readonly IUserService _userService;
        private readonly ITokenService _tokenService;
        private readonly IServiceProvider _serviceProvider;

        public App(IUserService userService, ITokenService tokenService, IServiceProvider serviceProvider)
        {
            InitializeComponent();
            _userService = userService;
            _tokenService = tokenService;
            _serviceProvider = serviceProvider;

            MainPage = new NavigationPage(_serviceProvider.GetRequiredService<LoadingPage>());
        }

        protected override async void OnStart()
        {
            base.OnStart();
            var token = await _tokenService.GetTokenAsync();
            if (!string.IsNullOrEmpty(token))
            {
                await _userService.LoadUserByIdAsync();
                if (_userService.CurrentUser is not null)
                {
                    MainPage = _serviceProvider.GetRequiredService<AppShell>();
                    return;
                }
            }
            MainPage = new NavigationPage(_serviceProvider.GetRequiredService<LoginPage>());
        }
    }
}