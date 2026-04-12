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

    protected function getMethodName(): string
    {
        return "convert";
    }

    protected function parseResult(): bool
    {
        $this->_result = $this->_rawresult;
        return true;
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
