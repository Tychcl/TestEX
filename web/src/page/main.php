<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--РУСУРСЫ-->
    <link rel="icon" href="_files/_images/Logo.svg" type="image/x-icon">
    <!--СТИЛИ-->
    <link rel="stylesheet" href="_files/_styles/base.css">
    <link rel="stylesheet" href="_files/_styles/main.css">
    <!--СКРИПТЫ-->
    <script src="_files/_scripts/main.js"></script>
    <title>Главная</title>
</head>
<body>
    <header>
        <div class="logo">
            <a href="/">ПЕРМСКИЙ<br>АВИАЦИОННЫЙ<br>ТЕХНИКУМ<br>им. А.Д. Швецова</a>
        </div>
         <div class="user-container">
           
        </div>
    </header>
    <main>
        <aside class="sidebar">
            <div class="sidebar-content">
                <nav class="main-nav">
                    <div class="folder">
                        <p>Чемпионат</p>
                        <button class="nav-link" onclick="infoadd()">
                            <img src="_files/_images/_sidebar/plus.svg" alt="plus"/>                         
                            <p>Добавить</p>
                        </button>
                        <button class="nav-link" onclick="eventadd()">
                            <img src="_files/_images/_sidebar/championship.svg"/>                         
                            <p>Учет</p>
                        </button>
                    </div>
                    <div class="folder">
                        <p>Категории</p>
                        <button class="nav-link" onclick="catadd()">
                            <img src="_files/_images/_sidebar/file.svg" alt="plus"/>                         
                            <p>Учет</p>
                        </button>
                    </div>
                    <div class="folder">
                        <p>Пользователь</p>
                        <button class="nav-link" onclick="signout()">
                            <img src="_files/_images/_sidebar/file.svg" alt="plus"/>                         
                            <p>Выйти</p>
                        </button>
                    </div>
                </nav>
            </div>
        </aside>
        <div id="page">
            <!--динамически загружаемая страница-->
        </div>
    </main>
    <footer>

    </footer>
</body>
</html>