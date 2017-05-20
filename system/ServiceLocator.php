<?php
namespace System;

final class ServiceLocator
{
    public function __get(string $service)
    {
        $service = ucfirst($service);

        if (isset($this->_services[$service]))
        {
            return $this->_services[$service];
        }
        else
        {
            throw new \Exception("ServiceLocator: Service {$service} is not in configuration!");
        }
    }

    public function __construct(array $services)
    {
        foreach($services as $service)
        {
            $serviceClass = "Services\\{$service->name}";

            $this->_services[$service->name] = new $serviceClass($service->parameters ?? null);
        }
    }

    private $_services = [];
}
?>