<?php
namespace Models;

class Login extends \System\BaseModel
{
    /* Определяет существование пользователь с данной парой логин-пароль */
    public function Validate(string $email, string $password) : bool
    {
        $statement = $this->_dataBase->prepare('SELECT `ID`, `Password` FROM `Users` WHERE `Email` = ?');
        $statement->execute([$email]);

        $result = $statement->fetch();

        if (!$result)
        {
            return false;
        }

        $this->_userId = $result->ID;

        return password_verify($password, $result->Password);
    }

    /* Получает всю информацию о пользователе по его идентификатору */
    public function GetUserInformation() : void
    {
        $statement = $this->_dataBase->prepare('SELECT * FROM `Users` WHERE `ID` = ?');
        $statement->execute([$this->_userId]);

        $user = $statement->fetch();

        $this->_services->user->id = $user->ID;
        $this->_services->user->name = $user->Name;
        $this->_services->user->email = $user->Email;

        $this->_services->user->SetPrivileges($user->Privileges);
    }

    /* Создает алиас для объекта соединения с базой данных */
    protected function _Initialize() : void
    {
        $this->_dataBase = $this->_services->dataBase->connection;
    }

    private $_userId = 0;

    private $_dataBase = null;
}
?>