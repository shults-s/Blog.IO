<?php
namespace Controllers;

class Show extends \System\BaseController
{
    /* Действие, вызываемое при открытии страницы со статьей */
    public function Article() : void
    {
        $id = $this->_request->GetUriParameter('id');

        $this->_model->SetParameters('Articles', $id);

        if ($this->_model->ValidParameters())
        {
            $renderer = $this->_services->renderer;

            $renderer->article = $this->_model->GetArticle();

            if (!$this->_services->user->LoggedIn())
            {
                $renderer->loggedIn = false;
            }
            else
            {
                $renderer->loggedIn = true;
                $renderer->name = $this->_services->user->name;
            }

            $html = $renderer->Render('show');
            $this->_response->SetContent($html);
        }
        else
        {
            $this->_response->SetError(404);
        }
    }
}
?>