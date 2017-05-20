<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog.IO — Вход в систему</title>
        <link rel="shortcut icon" href="/ui/images/icon.png" />
        <link rel="stylesheet" href="/ui/css/login.css" />
    </head>
    <body>
        <h1><a href="/" class="control" title="Назад">&larr;</a> Вход в систему</h1>
        <form action="/login/" method="post">
            <ul>
                <li><label>E-mail:</label><input type="email" name="email" required /></li>
                <li><label>Пароль:</label><input type="password" name="password" required /></li>
                <li class="submit"><input type="submit" value="&#10003; Войти" /></li>
                <?= $this->error ? '<li><p>Неверная пара логин-пароль!</p></li>' : null ?>
            </ul>
        </form>
    </body>
</html>