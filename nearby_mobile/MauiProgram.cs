using System.Net;
using Microsoft.Extensions.Logging;
using nearby_mobile.Interfaces;
using nearby_mobile.Services;
using nearby_mobile.ViewModels;
using nearby_mobile.Views;

namespace nearby_mobile
{
    public static class MauiProgram
    {
        public static MauiApp CreateMauiApp()
        {
            var builder = MauiApp.CreateBuilder();
            builder
                .UseMauiApp<App>()
                .ConfigureFonts(fonts =>
                {
                    fonts.AddFont("OpenSans-Regular.ttf", "OpenSansRegular");
                    fonts.AddFont("OpenSans-Semibold.ttf", "OpenSansSemibold");
                    fonts.AddFont("OpenSans-Bold.ttf", "OpenSansBold");
                });

#if DEBUG
    		builder.Logging.AddDebug();
#endif

            // Views
            builder.Services.AddTransient<LoginPage>();
            builder.Services.AddTransient<RegisterPage>();
            builder.Services.AddTransient<ProfilePage>();
            builder.Services.AddSingleton<AppShell>();

            // ViewModels
            builder.Services.AddTransient<LoginViewModel>();
            builder.Services.AddTransient<RegisterViewModel>();
            builder.Services.AddTransient<ProfileViewModel>();

            // Сервисы
            builder.Services.AddSingleton<ITokenService, TokenService>();
            builder.Services.AddSingleton<HttpClient>();
            builder.Services.AddSingleton<ApiClient>();
            builder.Services.AddSingleton<IAuthService, AuthService>();
            builder.Services.AddSingleton<IUserService, UserService>();

            return builder.Build();
        }
    }
}
