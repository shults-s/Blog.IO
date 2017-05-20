<?php
namespace Controllers;

class UserMain extends \System\BaseController
{
    /* Действие, вызываемое при открытии профиля пользователя */
    public function Index() : void
    {
        if ($this->_services->user->UserAccess())
        {
            $renderer = $this->_services->renderer;
            $renderer->name = $this->_services->user->name;

            $html = $renderer->Render('user');
            $this->_response->SetContent($html);
        }
        else
        {
            $this->_response->Redirect("/login/");
        }
    }
}
?>