<?php

declare(strict_types=1);

namespace UnoserverClient;

abstract class Client
{
    private $_host;
    private $_port;

    /**
     * Client constructor.
     * @param string $host
     * @param string $port
     */
    public function __construct(string $host = "127.0.0.1", string $port = "2003")
    {
        $this->_host = $host;
        $this->_port = $port;
    }

    /**
     * @return string - method name for call server
     */
    abstract protected function getMethodName(): string;
}
