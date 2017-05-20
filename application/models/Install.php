<?php
namespace Models;

class Install extends \System\BaseModel
{
    /* Создает в базе данных недостающие таблицы, руководствуясь их описанием */
    public function CreateTables() : void
    {
        $this->_dataBase->query("CREATE DATABASE IF NOT EXISTS `{$this->_services->dataBase->name}`");
        $this->_dataBase->query("USE `{$this->_services->dataBase->name}`");

        $reflection = new \ReflectionObject($this->_storage->tables);
        $tables = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($tables as $table)
        {
            $statement = $this->_dataBase->query("SHOW TABLES LIKE '{$table->getName()}'");

            if ($statement->rowCount() != 0)
            {
                continue;
            }
            else
            {
                $this->_CreateTable($table->getName());
            }
        }
    } 

    /* Создает в базе данных таблицу, руководствуясь ее описанием */
    private function _CreateTable(string $table) : void
    {
        $query = "CREATE TABLE `{$table}` (";

        foreach ($this->_storage->tables->{$table}->fields as $field)
        {
            $attributes = $field->attributes ?? null;
            $query .= "`{$field->name}` {$field->type} {$attributes} NOT NULL, ";
        }

        $query .= $this->_GetPrimaryForeignUniqueKeysSql($table);
        $query .= ") ENGINE = InnoDB CHARACTER SET = {$this->_services->dataBase->charset}";

        $this->_dataBase->query($query);
    }

    /* Экранирует кавычками каждое поле из списка, где поля разделены символами ' ,' */
    private function _EscapeFields(string &$fields) : void
    {
        if (strpos($fields, ',') !== false)
        {
            $fields = str_replace(', ', '`, `', $fields);
        }
    }

    /* Формирует часть SQL-запроса, содержащую первичный, уникальные и внешние ключи */
    private function _GetPrimaryForeignUniqueKeysSql(string $table) : string
    {
        $result = "PRIMARY KEY (`{$this->_storage->tables->{$table}->primaryKeys}`)";

        // Если первичный ключ составной, то каждое его поле экранируется кавычками отдельно
        $this->_EscapeFields($result);

        if (isset($this->_storage->tables->{$table}->foreignKeys))
        {
            foreach($this->_storage->tables->{$table}->foreignKeys as $key)
            {
                $result .= ", FOREIGN KEY ({$key->field}) REFERENCES {$key->table}({$key->foreignField})";
                $result .= ' ON DELETE NO ACTION ON UPDATE NO ACTION';
            }
        }

        if (isset($this->_storage->tables->{$table}->uniqueFields))
        {
            $uniqueFields = $this->_storage->tables->{$table}->uniqueFields;
            $this->_EscapeFields($uniqueFields);
            $result .= ", UNIQUE(`{$uniqueFields}`)";
        }

        return $result;
    }

    /* Загружает в хранилище список таблиц, создает алиас для объекта соединения с базой данных */
    protected function _Initialize() : void
    {
        $this->_storage = $this->_services->storage;
        $this->_storage->LoadJsonFile('tables');

        $this->_dataBase = $this->_services->dataBase->connection;
    }

    private $_storage = null;
    private $_dataBase = null;
}
?>