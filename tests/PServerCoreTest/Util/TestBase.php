<?php

namespace PServerCoreTest\Util;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class TestBase extends TestCase
{
    /** @var  string */
    protected $className;
    /** @var  array|null */
    protected $mockedMethodList = null;
    /** @var  MockObject */
    protected $class;
    /** @var array */
    protected $mockedConstructorArgList = [];

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @param $methodName
     * @return \ReflectionMethod
     */
    protected function getMethod($methodName)
    {
        $reflection = new ReflectionClass($this->getClass());
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @return MockObject
     */
    protected function getClass()
    {
        if (!$this->class) {
            $class = $this->getMockBuilder($this->className);
            if ($this->mockedConstructorArgList) {
                $class->setConstructorArgs($this->mockedConstructorArgList);
            } else {
                $class->disableOriginalConstructor();
            }
            $this->class = $class->setMethods($this->mockedMethodList)
                ->getMock();
        }

        return $this->class;
    }

    /**
     * @return array|null
     */
    protected function getMockedMethodList()
    {
        return $this->mockedMethodList;
    }
}