<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog.IO — Редактирование таблицы <?= $this->table ?></title>
        <link rel="shortcut icon" href="/ui/images/icon.png" />
        <link rel="stylesheet" href="/ui/css/error.css" />
    </head>
    <body>
        <h1>Редактирование таблицы <?= $this->table ?></h1>
        <p>
        <?php
            if ($this->success)
            {
                echo 'Изменения в таблице успешно сохранены!';
            }
            else
            {
                switch ($this->reason)
                {
                    case 1:
                        echo 'Запрашиваемая Вами таблица или запись в ней не существует!';
                        break;

                    case 2:
                        echo "При редактировании записи произошла ошибка:<br/>{$this->message}";
                        break;
                }
            }
        ?>
        </p>
        <p>Вы можете <a href="/admin/edit/<?= $this->table ?>/">вернуться назад</a>.</p>
    </body>
</html>