<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog.IO — Регистрация</title>
        <link rel="shortcut icon" href="/ui/images/icon.png" />
        <link rel="stylesheet" href="/ui/css/error.css" />
    </head>
    <body>
        <?php
            switch ($this->action)
            {
                case 1:
                echo '<h1>Регистрация прошла успешно!</h1><p>Теперь Вы можете <a href="/login/">войти в систему</a>.</p>';
                break;

                case 2:
                echo '<h1>Ваш аккаунт успешно удален!</h1><p>Вы можете <a href="/">вернуться на главную</a>.</p>';
                break;
            }
        ?>
    </body>
</html>