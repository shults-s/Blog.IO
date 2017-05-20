<?php
namespace System;

final class Configuration
{
    public function __isset(string $parameter) : bool
    {
        return isset($this->_parameters->$parameter);
    }

    public function __get(string $parameter)
    {
        if (isset($this->_parameters->$parameter))
        {
            return $this->_parameters->$parameter;
        }
        else
        {
            throw new \Exception("Configuration: Parameter {$parameter} is not set!");
        }
    }

    public function __construct(string $configuration)
    {
        $this->_parameters = json_decode($configuration);

        if ($this->_parameters === null)
        {
            throw new \Exception('Configuration: ' . json_last_error_msg() . '!');
        }
    }

    private $_parameters = [];
}
?>