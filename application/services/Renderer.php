<?php
namespace Services;

final class Renderer
{
    public function __set(string $parameter, $value) : void
    {
        $this->_parameters[$parameter] = $value;
    }

    public function __get(string $parameter)
    {
        if (isset($this->_parameters[$parameter]))
        {
            return $this->_parameters[$parameter];
        }
        else
        {
            throw new \Exception("Renderer: Parameter {$parameter} is not set!");
        }
    }

    public function __construct($parameters)
    {
        $this->_layoutsDirectory = $parameters->directory;
    }

    public function Render(string $layout) : string
    {
        $layoutFileName = "{$this->_layoutsDirectory}{$layout}.php";

        if (is_readable($layoutFileName))
        {
            ob_start();
            require $layoutFileName;
            return $this->_MinimizeHtml(ob_get_clean());
        }
        else
        {
            throw new \Exception("Renderer: Failed to load file {$layoutFileName}!");
        }
    }

    private function _MinimizeHtml(string $buffer) : string
    {
        $buffer = preg_replace('/>[\n]+/', '>', $buffer);
        $buffer = preg_replace('/ {2,}/', ' ', $buffer);
        $buffer = str_replace('> <', '><', $buffer);

        return $buffer;
    }

    private $_parameters = [];
    private $_layoutsDirectory = null;
}
?>