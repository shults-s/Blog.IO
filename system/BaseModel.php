<?php
namespace System;

abstract class BaseModel
{
    public function __construct(ServiceLocator $services)
    {
        $this->_services = $services;

        $this->_Initialize();
    }

    protected function _Initialize() : void {}

    protected $_services = null;
}
?>