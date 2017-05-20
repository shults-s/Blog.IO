<?php
namespace System;

final class FrontController
{
    public function __construct(HttpRequest $request, HttpResponse $response, ServiceLocator $services)
    {
        $this->_request = $request;
        $this->_response = $response;
        $this->_services = $services;
    }

    public function Run($route)
    {
        $modelClass = "Models\\{$route->controller}";
        $model = class_exists($modelClass) ? new $modelClass($this->_services) : null;

        $controllerClass = "Controllers\\{$route->controller}";
        $controller = new $controllerClass($this->_request, $this->_response, $this->_services, $model);

        if (is_callable([$controller, $route->action]))
        {
            $controller->{$route->action}();
        }
        else
        {
            throw new \Exception("FrontController: Method {$route->action} can not be called in class"
                . "{$route->controller}");
        }
    }

    private $_request = null;
    private $_response = null;
    private $_services = null;
}
?>