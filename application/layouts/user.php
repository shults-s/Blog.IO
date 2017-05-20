<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog.IO — Страница пользователя</title>
        <link rel="shortcut icon" href="/ui/images/icon.png" />
        <link rel="stylesheet" href="/ui/css/user.css" />
    </head>
    <body>
        <h1><a href="/" title="На главную" class="control">&larr;</a> Страница пользователя</h1>
        <h2><a href="/login/logout/" class="control" title="Выйти из аккаунта">&#128275;</a> Добро пожаловать, <?= $this->name ?>!</h2>
        <p>Это Ваша личная страничка! Здесь Вы можете <a href="#">написать статью</a>, <a href="#">создать категорию</a>, <a href="#">изменить пароль</a> или <a href="/register/delete/" onclick="return confirm('Вы уверены? Это действие невозможно будет отменить.');">удалить аккаунт</a>.</p>
    </body>
</html>