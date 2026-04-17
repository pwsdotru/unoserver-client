<?php

declare(strict_types=1);

namespace unit;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use UnoserverClient\Ping;

final class PingTest extends TestCase
{
    /**
     * @covers UnoserverClient\Ping::getMethodName
     */
    public function testGetMethodName(): void
    {
        $obj = new Ping();
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("getMethodName");
        $method->setAccessible(true);
        $result = $method->invoke($obj);
        self::assertEquals("info", $result);
    }

    /**
     * @covers UnoserverClient\Ping::validateInput
     */
    public function testValidateInput(): void
    {
        $obj = new Ping();
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("validateInput");
        $method->setAccessible(true);
        self::assertTrue($method->invoke($obj));
    }

    /**
     * @covers UnoserverClient\Ping::buildParams
     */
    public function testBuildParams(): void
    {
        $obj = new Ping();
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("buildParams");
        $method->setAccessible(true);
        self::assertEquals([], $method->invoke($obj));
    }
}
