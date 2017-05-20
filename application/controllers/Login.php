<?php
namespace Controllers;

class Login extends \System\BaseController
{
    /* Действие, вызываемое при открытии страницы с формой входа */
    public function ShowForm() : void
    {
        if ($this->_services->user->AdministratorAccess())
        {
            $this->_response->Redirect('/admin/');
        }
        else if ($this->_services->user->UserAccess())
        {
            $this->_response->Redirect('/user/');
        }
        else
        {
            $renderer = $this->_services->renderer;
            $renderer->error = false;
            $this->_response->SetContent($renderer->Render('login'));
        }
    }

    /* Действие, вызываемое при выходе пользователя из аккаунта */
    public function Logout() : void
    {
        $this->_services->user->Logout();
        $this->_response->Redirect('/');
    }

    /* Действие, вызываемое при отправке формы входа в систему */
    public function ProcessForm() : void
    {
        $email = $this->_request->GetPostParameter('email');
        $password = $this->_request->GetPostParameter('password');

        if ($this->_model->Validate($email, $password))
        {
            $this->_model->GetUserInformation();

            if ($this->_services->user->AdministratorAccess())
            {
                $this->_response->Redirect('/admin/');
            }
            else
            {
                $this->_response->Redirect('/user/');
            }
        }
        else
        {
            $renderer = $this->_services->renderer;
            $renderer->error = true;
            $this->_response->SetContent($renderer->Render('login'));
        }
    }
}
?>