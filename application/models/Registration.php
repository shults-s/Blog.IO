<?php
namespace Models;

class Registration extends \System\BaseModel
{
    /* Устанавливает параметры запроса: имя, e-mail и пароль */
    public function SetParameters(array $parameters) : void
    {
        $this->_parameters = $parameters;
    }

    /* Проверяет корректность переданных данных */
    public function Validate(?int &$reason) : bool
    {
        if (!isset($this->_parameters['Name'], $this->_parameters['Email'], $this->_parameters['Password']))
        {
            $reason = 2;
            return false;
        }

        $statement = $this->_dataBase->prepare('SELECT COUNT(*) FROM `Users` WHERE `Email` = ?');
        $statement->execute([ $this->_parameters['Email'] ]);

        if ($statement->fetchColumn() != 0)
        {
            $reason = 1;
            return false;
        }

        return true;
    }

    /* Производит удаление пользователя из базы данных */
    public function DeleteUser() : bool
    {
        $statement = $this->_dataBase->prepare('DELETE FROM `Users` WHERE `ID` = ?');

        try
        {
            $statement->execute([ $this->_services->user->id ]);

            $this->_services->user->Logout();

            return $statement->rowCount() != 0;
        }
        catch(\PDOException $exception)
        {
            return false;
        }
    }

    /* Производит регистрацию пользователя в базе данных */
    public function RegisterUser(?int &$reason) : bool
    {
        $statement = $this->_dataBase->prepare('INSERT INTO `Users` (`ID`, `Name`, `Email`, `Password`, '
            . '`Privileges`, `JoinDate`) VALUES (NULL, ?, ?, ?, \'u\', CURDATE())');

        $this->_parameters['Password'] = password_hash($this->_parameters['Password'], PASSWORD_BCRYPT);

        $reason = 2;

        try
        {
            $statement->execute(array_values($this->_parameters));
            return $statement->rowCount() != 0;
        }
        catch(\PDOException $exception)
        {
            return false;
        }
    }

    /* Создает алиас для объекта соединения с базой данных */
    protected function _Initialize() : void
    {
        $this->_dataBase = $this->_services->dataBase->connection;
    }

    private $_dataBase = null;

    private $_parameters = [];
}
?>