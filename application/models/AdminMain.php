<?php
namespace Models;

class AdminMain extends \System\BaseModel
{
    /* Получает из хранилища список таблиц базы данных */
    public function GetTables() : \Generator
    {
        $this->_services->storage->LoadJsonFile('tables');

        $reflection = new \ReflectionObject($this->_services->storage->tables);
        $tables = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($tables as $table)
        {
            yield $table->getName();
        }
    }
}
?>