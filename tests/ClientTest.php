<?php

declare(strict_types=1);

namespace Tests;

use ReflectionClass;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testContructDefault(): void
    {
        $obj = $this->getMockForAbstractClass('UnoserverClient\Client');
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
    public function testGetUrl(string $host, string $port, bool $ssl, string $url): void
    {
        $obj = $this->getMockForAbstractClass('UnoserverClient\Client', [$host, $port, $ssl]);
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("getUrl");
        $method->setAccessible(true);
        $result = $method->invoke($obj);
        self::assertEquals($url, $result);
    }

    public static function getUrlSet(): array
    {
        return [
            ["127.0.0.1", "2003", false, "http://127.0.0.1:2003"],
            ["host", "4000", true, "https://host:4000"],
        ];
    }

    protected static function getPrivateProperty(object $object, string $propertyName)
    {
        $reflectionClass = new ReflectionClass($object);
        $property = $reflectionClass->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }
}
