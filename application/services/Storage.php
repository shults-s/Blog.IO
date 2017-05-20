<?php
namespace Services;

final class Storage
{
    public function __get(string $jsonFile)
    {
        if (isset($this->_storage[$jsonFile]))
        {
            return $this->_storage[$jsonFile];
        }
        else
        {
            throw new \Exception("Storage: File {$jsonFile} has not been uploaded yet!");
        }
    }

    public function __construct($parameters)
    {
        $this->_storageDirectory = $parameters->directory;
    }

    public function LoadJsonFile(string $jsonFile)
    {
        if (!isset($this->_storage[$jsonFile]))
        {
            $this->_storage[$jsonFile] = $this->_ProcessJsonFile($jsonFile);
        }
        else
        {
            throw new \Exception("Storage: File {$jsonFile} was uploaded earlier!");
        }
    }

    private function _ProcessJsonFile(string $jsonFile)
    {
        $jsonFileName = "{$this->_storageDirectory}{$jsonFile}.json";

        if (!is_readable($jsonFileName))
        {
            throw new \Exception("Storage: Failed to load file {$jsonFileName}!");
        }
        else
        {
            $decodedJson = json_decode(file_get_contents($jsonFileName));
        }

        if ($decodedJson !== null)
        {
            return $decodedJson;
        }
        else
        {
            throw new \Exception('Storage: ' . json_last_error_msg() . " in file {$jsonFileName}!");
        }
    }

    private $_storage = [];
    private $_storageDirectory = null;
}
?>