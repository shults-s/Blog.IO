<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog.IO — Панель администратора</title>
        <link rel="shortcut icon" href="/ui/images/icon.png" />
        <link rel="stylesheet" href="/ui/css/admin.css" />
    </head>
    <body>
        <h1><a href="/" title="На главную" class="control">&larr;</a> Панель администратора</h1>
        <h2><a href="/login/logout/" class="control" title="Выйти из аккаунта">&#128275;</a> Добро пожаловать, <?= $this->name ?>!</h2>
        <ol>
            <?php
                foreach($this->tables as $table)
                {
                    echo "<li><a href=\"/admin/edit/{$table}/\">Редактировать</a> таблицу {$table}</li>";
                }
            ?>
        </ol>
    </body>
</html>