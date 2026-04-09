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
    /** @var array list of errors */
    protected $_errors = [];
    protected $_rawresult = null;
    protected $_result = null;

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
        if ($this->validateInput()) {
            $params = $this->buildParams();
            if ($this->makeXmlRpc($params)) {
                if ($this->parseResult()) {
                    return true;
                }
            }
        }
        return false;
    }


    public function result()
    {
        return $this->_result;
    }

    public function errors(): array
    {
        return $this->_errors;
    }

    protected function makeXmlRpc($params): bool
    {
        $request = xmlrpc_encode_request($this->getMethodName(), $params, ['encoding' => 'UTF-8']);
        $request = str_replace("<string/>", "<nil/>", $request);

        $response = $this->makeCurl($request);
        if (null === $response) {
            return false;
        }
        $rawresult = xmlrpc_decode($response, 'UTF-8');

        if (is_array($rawresult)) {
            if (xmlrpc_is_fault($rawresult)) {
                $this->_errors[] = sprintf(
                    "XML-RPC Fault with code  %s: %s \n",
                    (string)$rawresult['faultCode'],
                    $rawresult['faultString']
                );
                return false;
            }
        }

        $this->_rawresult = $rawresult;

        return true;
    }

    protected function makeCurl($request)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->_errors[] = sprintf("cURL error No %d %s\n", curl_errno($ch), curl_error($ch));
            $this->_errors[] = sprintf("\n\nServer response: \n \n %s \n", $response);
            $response = null;
        }
        curl_close($ch);

        return $response;
    }

    protected function getUrl(): string
    {
        $protocol = $this->_ssl ? "https" : "http";
        return $protocol . "://" . $this->_host . ":" . $this->_port;
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

    /**
     * @return bool - method return true if correct result of request
     */
    abstract protected function parseResult(): bool;
}
