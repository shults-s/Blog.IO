<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog.IO — Регистрация</title>
        <link rel="shortcut icon" href="/ui/images/icon.png" />
        <link rel="stylesheet" href="/ui/css/registration.css" />
    </head>
    <body>
        <h1><a href="/" class="control" title="Назад">&larr;</a> Регистрация</h1>
        <form action="/register/" method="post">
            <ul>
                <li><label>Имя:</label><input type="text" name="Name" required /></li>
                <li><label>E-mail:</label><input type="email" name="Email" required /></li>
                <li><label>Пароль:</label><input type="password" name="Password" required /></li>
                <li class="submit"><input type="submit" value="&#10003; Подтвердить" /></li>
                <?php
                    if ($this->error)
                    {
                        switch ($this->reason)
                        {
                            case 1:
                                echo '<li><p>Данный пользователь уже зарегистрирован!</p></li>';
                                break;

                            case 2:
                                echo '<li><p>При заполнении полей произошла ошибка!</p></li>';
                                break;
                        }
                    }
                ?>
            </ul>
        </form>
    </body>
</html>