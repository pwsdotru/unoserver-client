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

        self::assertEquals($empty, $this->getCompareParams($obj));
    }

    /**
     * @param $input
     * @param $expected
     * @throws \ReflectionException
     * @dataProvider setOutputFormatSet
     * @covers UnoserverClient\Compare::setOutputFormat
     */
    public function testSetOutputFormat($input, $expected): void
    {
        $obj = new Compare();
        $obj->setOutputFormat($input);
        $params = $this->getCompareParams($obj);
        self::assertEquals($expected, $params[5]);
    }

    public static function setOutputFormatSet(): array
    {
        return [
            ["pdf", "pdf"],
            ["DOCX", "docx"],
        ];
    }

    /**
     * @throws \ReflectionException
     * @covers UnoserverClient\Compare::loadOldFile
     */
    public function testLoadOldFile(): void
    {
        $file = dirname(__DIR__) . "/fixtures/test.txt";
        $obj = new Compare();
        $obj->loadOldFile($file);
        $params = $this->getCompareParams($obj);
        self::assertNotEmpty($params[1]);
        self::assertIsObject($params[1]);
        self::assertObjectHasProperty("scalar", $params[1]);
        self::assertStringEqualsFile($file, $params[1]->scalar);
    }

    /**
     * @throws \ReflectionException
     * @covers UnoserverClient\Compare::setOldData
     */
    public function testSetOldData(): void
    {
        $test = "test string";
        $obj = new Compare();
        $obj->setOldData($test);
        $params = $this->getCompareParams($obj);
        self::assertNotEmpty($params[1]);
        self::assertIsObject($params[1]);
        self::assertObjectHasProperty("scalar", $params[1]);
        self::assertEquals($test, $params[1]->scalar);
    }

    /**
     * @throws \ReflectionException
     * @covers UnoserverClient\Conpare::loadNewFile
     */
    public function testLoadNewFile(): void
    {
        $file = dirname(__DIR__) . "/fixtures/test.txt";
        $obj = new Compare();
        $obj->loadNewFile($file);
        $params = $this->getCompareParams($obj);
        self::assertNotEmpty($params[3]);
        self::assertIsObject($params[3]);
        self::assertObjectHasProperty("scalar", $params[3]);
        self::assertStringEqualsFile($file, $params[3]->scalar);
    }

    /**
     * @throws \ReflectionException
     * @covers UnoserverClient\Compare::setNewData
     */
    public function testSetNewData(): void
    {
        $test = "test string";
        $obj = new Compare();
        $obj->setNewData($test);
        $params = $this->getCompareParams($obj);
        self::assertNotEmpty($params[3]);
        self::assertIsObject($params[3]);
        self::assertObjectHasProperty("scalar", $params[3]);
        self::assertEquals($test, $params[3]->scalar);
    }
    /**
     * Service method for access to UnoserverClient\Compare::buildParams
     * @param Compare $obj
     * @return array
     * @throws \ReflectionException
     */
    protected function getCompareParams(Compare $obj): array
    {
        $reflectionClass = new ReflectionClass($obj);
        $method = $reflectionClass->getMethod("buildParams");
        $method->setAccessible(true);
        return $method->invoke($obj);
    }
}
