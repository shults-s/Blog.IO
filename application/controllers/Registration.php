<?php
namespace Controllers;

class Registration extends \System\BaseController
{
    /* Действие, вызываемое при открытии страницы с формой регистраии */
    public function ShowForm() : void
    {
        if ($this->_services->user->LoggedIn())
        {
            $this->_response->Redirect('/login/');
        }
        else
        {
            $renderer = $this->_services->renderer;
            $renderer->error = false;

            $html = $renderer->Render('registration/form');
            $this->_response->SetContent($html);
        }
    }

    /* Действие, вызываемое при удалении пользователя из базы данных */
    public function DeleteUser() : void
    {
        if (!$this->_services->user->LoggedIn())
        {
            $this->_response->Redirect('/');
        }
        else
        {
            $this->_model->DeleteUser();

            $renderer = $this->_services->renderer;
            $renderer->action = 2;

            $html = $renderer->Render('registration/notice');
            $this->_response->SetContent($html);
        }
    }

    /* Действие, вызываемое при отправке формы регистрации */
    public function ProcessForm() : void
    {
        $this->_model->SetParameters($this->_request->GetPostParameters());

        $renderer = $this->_services->renderer;
        $renderer->error = true;

        if (!$this->_model->Validate($reason))
        {
            $renderer->reason = $reason;
            $html = $renderer->Render('registration/form');
        }
        else if (!$this->_model->RegisterUser($reason))
        {
            $renderer->reason = $reason;
            $html = $renderer->Render('registration/form');
        }
        else
        {
            $renderer->action = 1;
            $html = $renderer->Render('registration/notice');
        }

        $this->_response->SetContent($html);
    }
}
?>