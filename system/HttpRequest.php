<?php
namespace System;

final class HttpRequest
{
    public function __construct(array $uriParameters)
    {
        $this->_postParameters = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $this->_uriParameters = $uriParameters;
    }

    public function GetUriParameter($parameter)
    {
        return $this->_uriParameters[$parameter] ?? null;
    }

    public function GetPostParameters() : array
    {
        return $this->_postParameters;
    }

    public function GetPostParameter($parameter)
    {
        return $this->_postParameters[$parameter] ?? null;
    }

    public static function GetMethod() : string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function GetProtocol() : string
    {
        return $_SERVER['SERVER_PROTOCOL'];
    }

    public static function GetUri() : string
    {
        return filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
    }

    private $_uriParameters = [];
    private $_postParameters = [];
}
?>