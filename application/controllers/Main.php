<?php
namespace Controllers;

class Main extends \System\BaseController
{
    /* Действие, вызываемое при открытии главной страницы приложения */
    public function Index() : void
    {
        $renderer = $this->_services->renderer;

        $renderer->articles = $this->_model->GetArticles();

        if (!$this->_services->user->LoggedIn())
        {
            $renderer->loggedIn = false;
        }
        else
        {
            $renderer->loggedIn = true;
            $renderer->name = $this->_services->user->name;
        }

        $html = $renderer->Render('main');
        $this->_response->SetContent($html);
    }
}
?>