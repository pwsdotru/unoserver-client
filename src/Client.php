<?php

declare(strict_types=1);

namespace UnoserverClient;

abstract class Client
{
    protected string $_host;
    protected string $_port;
    protected bool $_ssl;

    /** @var array list of params for xmlrpc call. Params is unnamed, so order is important */
    protected $fieldsList = [];
    protected $params = [];

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
     * Make call to API
     * @return bool
     */
    public function call(): bool
    {
        return true;
    }

    protected function buildParams(): array
    {
        $result = [];
        foreach ($this->fieldsList as $paramName) {
            $result[] = $this->params[$paramName] ?? null;
        }
        return $result;
    }
    /**
     * @return string - method name for call server
     */
    abstract protected function getMethodName(): string;

    /**
     * @return bool - method for validate input params
     */
    abstract protected function validateInput(): bool;
}
