<?php $recordId = join('-', $this->recordId); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog.IO — Редактирование таблицы <?= $this->table ?></title>
        <link rel="shortcut icon" href="/ui/images/icon.png" />
        <link rel="stylesheet" href="/ui/css/edit.css" />
    </head>
    <body>
        <h1><a href="/admin/" title="Назад" class="control">&larr;</a> Редактирование таблицы <?= $this->table ?></h1>
        <h2>Запись №<?= $recordId ? $recordId : '???' ?></h2>
        <form action="/admin/edit/<?= $this->table ?>/<?= $recordId ?>/" method="post">
            <ul>
                <?= $this->Render('edit/fields') ?>
                <?php
                    if ($recordId)
                    {
                        $mainButtonName = 'Save';
                        $mainButtonValue = 'Сохранить';

                        $auxiliaryButtonType = 'submit';
                        $auxiliaryButtonValue = 'Удалить';
                    }
                    else
                    {
                        $mainButtonName = 'Add';
                        $mainButtonValue = 'Добавить';

                        $auxiliaryButtonType = 'reset';
                        $auxiliaryButtonValue = 'Очистить';
                    }
                ?>
                <li>
                    <input type="submit" name="<?= $mainButtonName ?>" value="&#10003; <?= $mainButtonValue ?>" />
                    <input type="<?= $auxiliaryButtonType ?>" name="Delete" value="&#10007; <?= $auxiliaryButtonValue ?>" />
                </li>
                <?= $this->Render('edit/links') ?>
            </ul>
        </form>
    </body>
</html>