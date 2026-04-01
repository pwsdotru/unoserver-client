<?php

declare(strict_types=1);

namespace UnoserverClient;

class Compare extends Client
{
    protected function getMethodName(): string
    {
        return "compare";
    }

    protected function validateInput(): bool
    {
        return false;
    }
}
