<?php

declare(strict_types=1);

namespace unit;

use ReflectionClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use UnoserverClient\Client;

final class ClientTest extends TestCase
{
    public function testContructDefault(): void
    {
        $obj = $this->getClientMock();
        self::assertFalse(self::getPrivateProperty($obj, "_ssl"));
        self::assertEquals("2003", self::getPrivateProperty($obj, "_port"));
        self::assertEquals("127.0.0.1", self::getPrivateProperty($obj, "_host"));
    }

    /**
     * @param $host
     * @param $port
     * @param $ssl
     * @param $url
     * @dataProvider getUrlSet
     * @covers UnoserverClient\Client::getUrl
     */
    #[DataProvider('getUrlSet')]
    public function testGetUrl(string $host, string $port, bool $ssl, string $url): void
    {
        $obj = $this->getClientMock($host, $port, $ssl);
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("getUrl");
        $method->setAccessible(true);
        $result = $method->invoke($obj);
        self::assertEquals($url, $result);
    }

    /**
     * @return array <int, list<mixed>>
     */
    public static function getUrlSet(): array
    {
        return [
            ["127.0.0.1", "2003", false, "http://127.0.0.1:2003"],
            ["host", "4000", true, "https://host:4000"],
            ["http://127.0.0.1", "2003", false, "http://127.0.0.1:2003"],
            ["https://unoserver.loc", "2010", true, "https://unoserver.loc:2010"],
        ];
    }

    /**
     * @throws \ReflectionException
     * @covers UnoserverClient\Client::readFile
     */
    public function testReadFileSuccess(): void
    {
        $file = dirname(__DIR__) . "/fixtures/test.txt";
        $obj = $this->getClientMock();
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("readFile");
        $method->setAccessible(true);
        $data = $method->invoke($obj, $file);
        self::assertStringEqualsFile($file, $data);
    }

    /**
     * @throws \ReflectionException
     * @covers UnoserverClient\Client::readFile
     */
    public function testReadFileFail(): void
    {
        $obj = $this->getClientMock();
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("readFile");
        $method->setAccessible(true);
        $data = $method->invoke($obj, __DIR__ . "/fixtures/notfound.txt");
        self::assertNull($data);
        self::assertNotEmpty(self::getPrivateProperty($obj, '_errors'));
    }

    /**
     * @covers UnoserverClient\Client::saveFile
     */
    public function testSaveFileFail(): void
    {
        $obj = $this->getClientMock();
        $result = $obj->saveFile("empty.txt");
        self::assertFalse($result);
        self::assertNotEmpty(self::getPrivateProperty($obj, '_errors'));
    }

    /**
     * @throws \ReflectionException
     * @covers UnoserverClient\Client::logError
     * @covers UnoserverClient\Client::errors
     */
    public function testLogError(): void
    {
        $obj = $this->getClientMock();
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("logError");
        $method->setAccessible(true);
        $method->invoke($obj, "Test error");
        self::assertEquals(["Test error"], $obj->errors());
        $method->invoke($obj, "Test error 2");
        self::assertEquals(["Test error", "Test error 2"], $obj->errors());
    }

    /**
     * @param string $host
     * @param string $port
     * @param bool $ssl
     * @return Client
     */
    protected function getClientMock(string $host = "127.0.0.1", string $port = "2003", bool $ssl = false)
    {
        return new class ($host, $port, $ssl) extends Client {
            protected function getMethodName(): string
            {
                return "Mock";
            }
            protected function validateInput(): bool
            {
                return true;
            }
            protected function parseResult(): bool
            {
                return true;
            }
        };
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @return mixed
     * @throws \ReflectionException
     */
    protected static function getPrivateProperty(object $object, string $propertyName)
    {
        $reflectionClass = new ReflectionClass($object);
        $property = $reflectionClass->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }
}
