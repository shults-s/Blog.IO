<li>
<?php
    // При добавлении новой записи в базу данных ссылки не выводятся
    if ($this->recordId != [ 0 ])
    {
        $previous = join('-', $this->previous);
        $next = join('-', $this->next);

        $table = $this->table;

        if ($previous)
        {
            $backward = 'Предыдущая запись';
            $backSymbol = '&larr;';
        }
        else
        {
            $backward = 'Добавить запись';
            $backSymbol = '&#65291;';
        }

        if ($next)
        {
            $forward = 'Следующая запись';
            $nextSymbol = '&rarr;';
        }
        else
        {
            $forward = 'Добавить запись';
            $nextSymbol = '&#65291;';
        }

        echo "<p>{$backSymbol} <a href=\"/admin/edit/{$table}/{$previous}\">{$backward}</a></p>";
        echo "<p><a href=\"/admin/edit/{$table}/{$next}\">{$forward}</a> {$nextSymbol}</p>";
    }
?>
</li>