<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--РУСУРСЫ-->
    <link rel="icon" href="_files/_images/logo.svg" type="image/x-icon">
    <!--СТИЛИ-->
    <link rel="stylesheet" href="_files/_styles/base.css">
    <link rel="stylesheet" href="_files/_styles/auth.css">
    <!--СКРИПТЫ-->
    <script src="_files/_scripts/_pages/auth.js"></script>
    <script src="_files/_scripts/main.js"></script>
    <title>Авторизация</title>
</head>
<body>
    <header>
        <div class="logo">
            <a href="/">ПЕРМСКИЙ<br>АВИАЦИОННЫЙ<br>ТЕХНИКУМ<br>им. А.Д. Швецова</a>
        </div>
    </header>
    <main class="auth-main">
        <div class="auth-form-container">
            <div class="auth-header">
                <h1 class="auth-title">Авторизация</h1>
                <p class="auth-subtitle">Введите ваши учетные данные для входа в систему</p>
            </div>
            
            <form id="auth-form" class="auth-form" method="POST">
                <div class="form-group-authorization">
                    <label for="login" class="form-label">Логин</label>
                    <input 
                        name="login"
                        id="login" 
                        type="text" 
                        class="form-input"
                        placeholder="Введите ваш логин"
                        required>
                    
                </div>
                <div class="form-group-authorization password-group">
                    <label for="password" class="form-label-authorization">Пароль</label>
                    <div class="password-input-wrapper">
                        <input 
                            name="password"
                            id="password" 
                            type="password" 
                            class="form-input password-input"
                            placeholder="Введите ваш пароль" 
                            required
                        >
                        <a href="#" class="password-control" onclick="togglePassword()"></a>
                    </div>
                </div>
                <button type="submit" class="auth-button">
                    <span class="button-text">Войти</span>
                    <span class="button-loader" style="display: none;">⏳</span>
                </button>
            </form>
        </div>
    </main>
</body>
</html>