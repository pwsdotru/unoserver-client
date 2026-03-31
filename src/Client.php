<?php

declare(strict_types=1);

namespace UnoserverClient;

abstract class Client
{
    protected string $_host;
    protected string $_port;
    protected bool $_ssl;

    /**
     * Client constructor.
     * @param string $host
     * @param string $port
     */
    public function __construct(string $host = "127.0.0.1", string $port = "2003", bool $ssl = false)
    {
        $this->_host = $host;
        $this->_port = $port;
        $this->_ssl = $ssl;
    }

    /**
     * @return string - method name for call server
     */
    abstract protected function getMethodName(): string;
}
