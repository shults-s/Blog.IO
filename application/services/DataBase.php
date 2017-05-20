<?php
namespace Services;

use \PDO;

final class DataBase
{
    public $connection = null;
    public $charset = null;
    public $name = null;

    public function __construct($parameters)
    {
        $this->charset = $parameters->charset;
        $this->name = $parameters->name;

        $dsn = "mysql:host={$parameters->host};dbname={$this->name};charset={$this->charset}";

        // Режим разворачивания приложения, база данных не задействуется, т. к. ее еще нет
        if ($this->name[0] == '*')
        {
            $dsn = "mysql:host={$parameters->host}";
            $this->name = substr($this->name, 1);
        }

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];

        $this->connection = new PDO($dsn, $parameters->user, $parameters->password, $options);
    }
}
?>