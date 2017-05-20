<?php
namespace System;

final class HttpResponse
{
    private const STATUS_CODE_MESSAGES = [
        500 => ' 500 Internal Server Error',
        404 => ' 404 Not Found',
        403 => ' 403 Forbidden'
    ];

    public function __construct($errorPagesFileNames)
    {
        $this->_errorPagesFileNames = $errorPagesFileNames;
    }

    public function SetError(int $code) : void
    {
        if (!isset(self::STATUS_CODE_MESSAGES[$code]))
        {
            throw new \Exception("HttpResponse: Status code {$code} is not supported!");
        }
        else
        {
            $this->_statusCode = $code;
            $errorPageFileName = $this->_errorPagesFileNames->{$this->_statusCode};

            if (is_readable($errorPageFileName))
            {
                $this->_content = file_get_contents($errorPageFileName);
            }
            else
            {
                throw new \Exception("HttpResponse: Failed to load file {$errorPageFileName}!");
            }
        }
    }

    public function SetContent(string $content) : void
    {
        $this->_content = $content;
    }

    public function Redirect(string $location) : void
    {
        $this->_headers[] = 'Location: ' . $location;
    }

    public function Send(bool $preventCaching = false) : void
    {
        if ($preventCaching)
        {
            $this->_headers[] = 'Cache-Control: no-cache, must-revalidate';
            $this->_headers[] = 'Expires: Thu, 01 Jan 1970 00:00:00 GMT';
        }

        if ($this->_statusCode === null)
        {
            foreach ($this->_headers as $header)
            {
                header($header);
            }
        }
        else
        {
            header(HttpRequest::GetProtocol() . self::STATUS_CODE_MESSAGES[$this->_statusCode]);
        }

        echo($this->_content); // exit
    }

    private $_content = null;
    private $_headers = null;
    private $_statusCode = null;
    private $_errorPagesFileNames = [];
}
?>