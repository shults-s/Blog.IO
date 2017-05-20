<?php
namespace System;

final class Router
{
    private const PATTERNS = ['string' => '[a-zA-Z0-9_\-\+%]+', 'int' => '[0-9]+'];

    public function __construct(array $routes)
    {
        $this->_routes = $routes;
    }

    public function Match(&$matchedRoute, &$request) : bool
    {
        $requestMethod = HttpRequest::GetMethod();
        $requestUri = HttpRequest::GetUri();

        foreach($this->_routes as $route)
        {
            $methodsEqual = $requestMethod == ($route->method ?? 'GET');
            $convertedRoute = $this->_ConvertRoute($route->pattern);

            if ($methodsEqual && preg_match($convertedRoute, $requestUri, $requestParameters))
            {
                $matchedRoute = $route;
                $request = new HttpRequest($requestParameters);
                return true;
            }
        }

        return false;
    }

    private function _FilterParameters($parameters)
    {
        foreach ($parameters as $key => $value)
        {
            if (is_int($key))
            {
                unset($parameters[$key]);
            }
        }

        return $parameters;
    }

    private function _ConvertParameter(array $matches) : string
    {
        return "(?<{$matches[1]}>" . strtr($matches[2], self::PATTERNS) . ')';
    }

    private function _ConvertRoute($route) : string
    {
        $route = "#^{$route}?$#";

        if (!strpos($route, '{'))
        {
            return $route;
        }
        else
        {
            // {parameterName:pattern} --> (?<parameterName>regularExpression)
            return preg_replace_callback('/{(\w+):(\w+)}/', [$this, '_ConvertParameter'], $route);
        }
    }

    private $_routes = [];
}
?>