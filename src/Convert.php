<?php

declare(strict_types=1);

namespace UnoserverClient;

class Convert extends Client
{
    protected function getMethodName(): string
    {
        return "convert";
    }
}
