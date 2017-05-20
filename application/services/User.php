<?php
namespace Services;

final class User
{
    public function __set(string $parameter, $value) : void
    {
        if ($parameter != 'privileges')
        {
            $_SESSION[$parameter] = $value;
        }
        else
        {
            throw new \Exception("User: User privileges cannot be changed!");
        }
    }

    public function __get(string $parameter)
    {
        if (isset($_SESSION[$parameter]))
        {
            return $_SESSION[$parameter];
        }
        else
        {
            throw new \Exception("User: Parameter {$parameter} is not set!");
        }
    }

    public function __construct()
    {
        session_start();
    }

    public function Logout() : void
    {
        session_destroy();
    }

    public function AdministratorAccess() : bool
    {
        return ($_SESSION['privileges'] ?? null) == 'a';
    }

    public function UserAccess() : bool
    {
        return ($_SESSION['privileges'] ?? null) == 'u';
    }

    public function LoggedIn() : bool
    {
        return $this->AdministratorAccess() || $this->UserAccess();
    }

    public function SetPrivileges(string $privileges) : void
    {
        if (!isset($_SESSION['privileges']))
        {
            $_SESSION['privileges'] = $privileges;
        }
        else
        {
            throw new \Exception("User: User privileges cannot be changed!");
        }
    }
}
?>