<?php
namespace System;

abstract class BaseController
{
    public function __construct(HttpRequest $request, HttpResponse $response,
        ServiceLocator $services, ?BaseModel $model)
    {
        $this->_services = $services;
        $this->_request = $request;
        $this->_model = $model;
        $this->_response = $response;

        $this->_Initialize();
    }

    protected function _Initialize() : void {}

    protected $_model = null;
    protected $_request = null;
    protected $_response = null;
    protected $_services = null;
}
?>