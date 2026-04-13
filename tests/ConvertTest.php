<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use UnoserverClient\Convert;

final class ConvertTest extends TestCase
{
    /**
     * @covers UnoserverClient\Convert::getMethodName
     */
    public function testGetMethodName(): void
    {
        $obj = new Convert();
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("getMethodName");
        $method->setAccessible(true);
        $result = $method->invoke($obj);
        self::assertEquals("convert", $result);
    }

    /**
     * @covers UnoserverClient\Conver::validateInput
     */
    public function testValidateInput(): void
    {
        $obj = new Convert();
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("validateInput");
        $method->setAccessible(true);
        self::assertFalse($method->invoke($obj));
        self::assertNotEmpty($obj->errors());
    }

    /**
     * @covers UnoserverClient\Convert::buildParams
     */
    public function testBuildParams(): void
    {
        $empty = [null, null, null, null, null, [], true, null, null];
        $obj = new Convert();

        self::assertEquals($empty, $this->getConvertParams($obj));
    }

    /**
     * @param $input
     * @param $expected
     * @throws \ReflectionException
     * @dataProvider setOutputFormatSet
     * @covers UnoserverClient\Convert::setOutputFormat
     */
    public function testSetOutputFormat($input, $expected): void
    {
        $obj = new Convert();
        $obj->setOutputFormat($input);
        $params = $this->getConvertParams($obj);
        self::assertEquals($expected, $params[3]);
    }

    public static function setOutputFormatSet(): array
    {
        return [
            ["pdf", "pdf"],
            ["JPG", "jpg"],
        ];
    }

    /**
     * Service method for access to UnoserverClient\Convert::buildParams
     * @param Convert $obj
     * @return array
     * @throws \ReflectionException
     */
    protected function getConvertParams(Convert $obj): array
    {
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("buildParams");
        $method->setAccessible(true);
        return $method->invoke($obj);
    }
}
