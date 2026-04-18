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
