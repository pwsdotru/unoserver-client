<?php

declare(strict_types=1);

namespace UnoserverClient;

class Convert extends Client
{
    protected $fieldsList = [
        0 => "inpath",
        1 => "indata",
        2 => "outpath",
        3 => "convert_to",
        4 => "filtername",
        5 => "filter_options",
        6 => "update_index",
        7 => "infiltername",
        8 => "password",
    ];

    protected $params = [
        "inpath" => null,
        "indata" => null,
        "outpath" => null,
        "convert_to" => null,
        "filtername" => null,
        "filter_options" => [],
        "update_index" => true,
        "infiltername" => null,
        "password" => null,
    ];

    /**
     * Set output format for convert
     * @param string $format
     */
    public function setOutputFormat(string $format): void
    {
        $this->params["convert_to"] = strtolower($format);
    }

    /**
     * Set input binary data for convert
     * @param string $data
     */
    public function setInputData(string $data): void
    {
        xmlrpc_set_type($data, "base64");
        $this->params["indata"] = $data;
    }

    /**
     * Load file for convert
     * @param string $filename
     * @return bool
     */
    public function loadFile(string $filename): bool
    {
        $data = $this->readFile($filename);
        if (null !== $data) {
            $this->setInputData($data);
            return true;
        }
        return false;
    }

    protected function getMethodName(): string
    {
        return "convert";
    }

    protected function parseResult(): bool
    {
        if (
            is_object($this->_rawresult) && property_exists($this->_rawresult, 'scalar') &&
            property_exists($this->_rawresult, 'xmlrpc_type') && $this->_rawresult->xmlrpc_type === 'base64'
        ) {
            $this->_result = $this->_rawresult->scalar;
            return true;
        }
        $this->logError(sprintf("Wrong return result"));
        return false;
    }

    protected function validateInput(): bool
    {
        $err = 0;
        if (empty($this->params["indata"])) {
            $this->logError(sprintf("You should define source data for convert"));
            $err++;
        }

        if (empty($this->params["convert_to"])) {
            $this->logError(sprintf("You should define format for output"));
            $err++;
        }

        return 0 === $err;
    }
}
