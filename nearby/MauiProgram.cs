using Microsoft.Extensions.Logging;

using nearby.Interfaces;
using nearby.Services;
using nearby.Classes;
using nearby.ViewModels;
using nearby.Views;
using nearby.Views.Auth;
using nearby.Views.Main;
using nearby.Views.Additional;

namespace nearby
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
                    fonts.AddFont("OpenSans-Bold.ttf", "OpenSansBold");
                    fonts.AddFont("OpenSans-Regular.ttf", "OpenSansRegular");
                    fonts.AddFont("OpenSans-Semibold.ttf", "OpenSansSemibold");
                });

#if DEBUG
    		builder.Logging.AddDebug();
#endif
            //=====================
            //Сервисы
            //=====================
            builder.Services.AddSingleton<HttpClient>();
            builder.Services.AddSingleton<ApiClient>();
            builder.Services.AddSingleton<ITokenService, TokenService>();
            builder.Services.AddSingleton<IUserService, UserService>();
            builder.Services.AddTransient<IAuthService, AuthService>();
            builder.Services.AddSingleton<ITaskService, TaskService>();
            builder.Services.AddSingleton<IChatService, ChatService>();

            //=====================
            //ВиевМодели
            //=====================
            //аунтефикация
            builder.Services.AddTransient<LoginViewModel>();
            builder.Services.AddTransient<RegViewModel>();

            builder.Services.AddTransient<ChatsViewModel>();
            builder.Services.AddTransient<ChatDetailViewModel>();
            builder.Services.AddTransient<EditProfileViewModel>();
            builder.Services.AddTransient<ProfileViewModel>();
            builder.Services.AddTransient<TasksViewModel>();
            builder.Services.AddTransient<TaskDetailViewModel>();

            //=====================
            //Страницы
            //=====================
            builder.Services.AddTransient<LoadingPage>();
            //аунтефикация
            builder.Services.AddTransient<LoginPage>();
            builder.Services.AddTransient<RegPage>();
            builder.Services.AddTransient<AuthShell>();
            //основные
            builder.Services.AddTransient<ProfilePage>();
            builder.Services.AddTransient<TasksPage>();
            builder.Services.AddTransient<MainShell>();
            builder.Services.AddTransient<ChatsPage>();
            //дополнительные
            builder.Services.AddTransient<EditProfilePage>();
            builder.Services.AddTransient<TaskDetailPage>();
            builder.Services.AddTransient<ChatDetailPage>();

            return builder.Build();
        }
    }
}
