<?php
namespace Controllers;

class AdminEdit extends \System\BaseController
{
    /* Действие, вызываемое при отправке формы редактирования таблицы */
    public function ProcessForm() : void
    {
        if ($this->_initialized)
        {
            $formData = $this->_request->GetPostParameters();

            $actions = [
                'Add'    => [$this->_model, 'InsertRecord'],
                'Save'   => [$this->_model, 'UpdateRecord'],
                'Delete' => [$this->_model, 'DeleteRecord']
            ];

            $success = false;

            foreach($actions as $action => $callback)
            {
                if (isset($formData[$action]))
                {
                    unset($formData[$action]);
                    $success = call_user_func($callback, $formData);
                    break;
                }
            }

            $renderer = $this->_services->renderer;
            $renderer->success = $success;

            if (!$success)
            {
                $renderer->reason = 2;
                $renderer->message = $this->_model->GetLastErrorMessage();
            }

            $this->_response->SetContent($renderer->Render('edit/notice'));
        }
    }

    /* Действие, вызываемое при отображении формы редактирования таблицы */
    public function ShowForm() : void
    {
        if ($this->_initialized)
        {
            $renderer = $this->_services->renderer;

            $renderer->values = $this->_model->GetValues();
            $renderer->fields = $this->_model->GetFields();

            $this->_model->GetNeighboringRecords($previous, $next);
            $renderer->previous = $previous;
            $renderer->next = $next;

            $html = $renderer->Render('edit/edit');

            $this->_response->SetContent($html);
        }
    }

   /*
    * Создает алиасы для параметров запроса, загружает параметры в модель,
    * проверяет корректность данных запроса и права доступа
    */
    protected function _Initialize() : void
    {
        $this->_table = $this->_request->GetUriParameter('table');

        if ($this->_request->GetUriParameter('id1') !== null)
        {
            $this->_record[] = $this->_request->GetUriParameter('id1');
        }

        if ($this->_request->GetUriParameter('id2') !== null)
        {
            $this->_record[] = $this->_request->GetUriParameter('id2');
        }

        $this->_model->SetParameters($this->_table, $this->_record);

        $this->_services->renderer->table = $this->_table;
        $this->_services->renderer->recordId = $this->_record;

        if (!$this->_services->user->AdministratorAccess())
        {
            $this->_initialized = false;

            $this->_response->Redirect("/login/");
        }
        else if (!$this->_model->Validate($tableIsEmpty))
        {
            $this->_initialized = false;

            if (!$tableIsEmpty)
            {
                $this->_services->renderer->success = false;
                $this->_services->renderer->reason = 1;

                $html = $this->_services->renderer->Render('edit/notice');

                $this->_response->SetContent($html);
            }
            else
            {
                $this->_response->Redirect("/admin/edit/{$this->_table}/0");
            }
        }
    }

    private $_table = null;
    private $_record = [];
    private $_initialized = true;
}
?>