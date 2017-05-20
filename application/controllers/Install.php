<?php
namespace Controllers;

class Install extends \System\BaseController
{
    /* Действие, вызываемое при первом запуске приложения */
    public function Index() : void
    {
        $this->_model->CreateTables();

        $html = $this->_services->renderer->Render('install');
        $this->_response->SetContent($html);
    }
}
?>