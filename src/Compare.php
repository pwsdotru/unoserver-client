<?php

declare(strict_types=1);

namespace UnoserverClient;

class Compare extends Client
{
    protected $fieldsList = [
        0 => "oldpath",
        1 => "olddata",
        2 => "newpath",
        3 => "newdata",
        4 => "outpath",
        5 => "filetype",
    ];

    protected $params = [
        "oldpath" => null,
        "olddata" => null,
        "newpath" => null,
        "newdata" => null,
        "outpath" => null,
        "filetype" => null,
    ];

    /**
     * Set output format for output
     * @param string $format
     */
    public function setOutputFormat(string $format): void
    {
        $this->params["filetype"] = strtolower($format);
    }

    /**
     * Set input binary data for old file
     * @param string $data
     */
    public function setOldData(string $data): void
    {
        xmlrpc_set_type($data, "base64");
        $this->params["olddata"] = $data;
    }

    /**
     * Set input binary data for new file
     * @param string $data
     */
    public function setNewData(string $data): void
    {
        xmlrpc_set_type($data, "base64");
        $this->params["newdata"] = $data;
    }

    /**
     * Load file for old file
     * @param string $filename
     * @return bool
     */
    public function loadOldFile(string $filename): bool
    {
        $data = $this->readFile($filename);
        if (null !== $data) {
            $this->setOldData($data);
            return true;
        }
        return false;
    }

    /**
     * Load file for new file
     * @param string $filename
     * @return bool
     */
    public function loadNewFile(string $filename): bool
    {
        $data = $this->readFile($filename);
        if (null !== $data) {
            $this->setNewData($data);
            return true;
        }
        return false;
    }

    protected function getMethodName(): string
    {
        return "compare";
    }

    protected function validateInput(): bool
    {
        $err = 0;
        if (empty($this->params["olddata"])) {
            $this->logError(sprintf("You should define source data for old file"));
            $err++;
        }

        if (empty($this->params["newdata"])) {
            $this->logError(sprintf("You should define source data for new file"));
            $err++;
        }

        if (empty($this->params["filetype"])) {
            $this->logError(sprintf("You should define format for output"));
            $err++;
        }

        return 0 === $err;
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
}
