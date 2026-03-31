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

    protected static function getPrivateProperty(object $object, string $propertyName)
    {
        $reflectionClass = new ReflectionClass($object);
        $property = $reflectionClass->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }
}
