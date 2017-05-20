<?php
namespace Controllers;

class AdminMain extends \System\BaseController
{
    /* Действие, вызываемое при открытии панели администратора */
    public function Index() : void
    {
        // Проверяем, есть ли у зашедшего привилегии администратора
        if ($this->_services->user->AdministratorAccess())
        {
            $renderer = $this->_services->renderer;

            $renderer->tables = $this->_model->GetTables();
            $renderer->name = $this->_services->user->name;

            $this->_response->SetContent($renderer->Render('admin'));
        }
        else
        {
            $this->_response->Redirect("/login/");
        }
    }
}
?>