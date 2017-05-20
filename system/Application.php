<?php
namespace System;

final class Application
{
    public function __construct(string $configuration)
    {
        try
        {
            spl_autoload_register([$this, '_Autoload']);

            $this->_configuration = new Configuration($configuration);
            $this->_response = new HttpResponse($this->_configuration->errorPagesFileNames);

            $router = new Router($this->_configuration->routes);

            if ($router->Match($route, $request))
            {
                $services = new ServiceLocator($this->_configuration->services);

                $controller = new FrontController($request, $this->_response, $services);
                $controller->Run($route);
            }
            else
            {
                $this->_response->SetError(404);
            }

            $this->_response->Send($this->_configuration->debugMode);
        }
        catch (\Exception $exception)
        {
            $this->_ProcessFatalException($exception);
        }
    }

    private function _ProcessFatalException(\Exception $exception) : void
    {
        $message = $exception->getMessage() . PHP_EOL . $exception->getTraceAsString();

        if (isset($this->_configuration->debugMode) && $this->_configuration->debugMode)
        {
            exit($message);
        }
        else
        {
            error_log($message);
            exit('The site is currently unavailable. Please check later!');
        }
    }

    private function _Autoload(string $className) : void
    {
        [$namespace, $class] = explode('\\', $className);

        $parentClass = null;

        // Является ли наличие класса критически важным условием для продолжения работы приложения
        $isCritical = true;

        switch($namespace)
        {
            case 'Controllers':
                $directory = $this->_configuration->directories->controllers;
                $parentClass = '\System\BaseController';
                break;

            case 'Models':
                $directory = $this->_configuration->directories->models;
                $parentClass = '\System\BaseModel';
                $isCritical = false;
                break;

            case 'Services':
                $directory = $this->_configuration->directories->services;
                break;

            case 'System':
                $directory = './system/';
                break;

            default:
                throw new \Exception("Autoload: Namespace {$namespace} is not supported!");
        }

        $classFileName = "{$directory}{$class}.php";

        if (is_readable($classFileName))
        {
            require $classFileName;
        }
        else if (!$isCritical)
        {
            return ;
        }
        else
        {
            throw new \Exception("Autoload: Failed to load file {$classFileName}!");
        }

        if (!class_exists($className, false))
        {
            throw new \Exception("Autoload: Class {$className} not found in {$classFileName}!");
        }

        if ($parentClass !== null && !is_subclass_of($className, $parentClass))
        {
            throw new \Exception("Autoload: Class {$className} does not inherit {$parentClass}!");
        }
    }

    private $_response = null;
    private $_configuration = null;
}
?>