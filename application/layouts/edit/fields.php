<?php
    foreach($this->fields as $field)
    {
        $name = $field->name;
        $label = $field->inputLabel ?? null;
        $type = $field->inputType ?? null;
        $value = $this->values->$name ?? null;

        // Поля таблицы, у которых не указан тип поля ввода, не выводятся
        if ($type === null)
        {
            continue;
        }
        else if ($type == 'textarea')
        {
            $field = "<textarea name=\"{$name}\" required>{$value}</textarea>";
        }
        else
        {
            $field = "<input type=\"{$type}\" name=\"{$name}\" value=\"{$value}\" required />";
        }

        echo "<li><label>{$label}:</label>{$field}</li>";
    }
?>