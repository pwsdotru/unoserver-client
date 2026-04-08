<?php

declare(strict_types=1);

namespace UnoserverClient;

class Ping extends Client
{
    protected function getMethodName(): string
    {
        return "info";
    }

    protected function validateInput(): bool
    {
        return true;
    }

    protected function parseResult(): bool
    {
        $this->_result = $this->_rawresult;
        return true;
    }
}
