<?php

declare(strict_types=1);

namespace unit;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use UnoserverClient\Compare;

final class CompareTest extends TestCase
{
    /**
     * @covers UnoserverClient\Compare::getMethodName
     */
    public function testGetMethodName(): void
    {
        $obj = new Compare();
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("getMethodName");
        $method->setAccessible(true);
        $result = $method->invoke($obj);
        self::assertEquals("compare", $result);
    }

    /**
     * @covers UnoserverClient\Compare::validateInput
     */
    public function testValidateInput(): void
    {
        $obj = new Compare();
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("validateInput");
        $method->setAccessible(true);
        self::assertFalse($method->invoke($obj));
        self::assertNotEmpty($obj->errors());
    }

    /**
     * @covers UnoserverClient\Compare::buildParams
     */
    public function testBuildParams(): void
    {
        $empty = [null, null, null, null, null, null];
        $obj = new Compare();

        self::assertEquals($empty, $this->getConvertParams($obj));
    }

    /**
     * Service method for access to UnoserverClient\Convert::buildParams
     * @param Compare $obj
     * @return array
     * @throws \ReflectionException
     */
    protected function getConvertParams(Compare $obj): array
    {
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("buildParams");
        $method->setAccessible(true);
        return $method->invoke($obj);
    }
}
