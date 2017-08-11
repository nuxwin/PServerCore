<?php

namespace PServerCoreTest\Service;

use PServerCoreTest\Util\TestBase;

class TimerTest extends TestBase
{
    /** @var  string */
    protected $className = \PServerCore\Service\Timer::class;

    public function setUp()
    {
        parent::setUp();
        date_default_timezone_set('UTC');
    }

    public function testGetNextTimeSameDay()
    {
        $this->mockedMethodList = [
            'getCurrentTimeStamp'
        ];
        /** @var \PServerCore\Service\Timer|\PHPUnit_Framework_MockObject_MockObject $class */
        $class = $this->getClass();

        $class->expects($this->any())
            ->method('getCurrentTimeStamp')
            ->willReturn(1437082616);

        $hourList = [
            23,
            2
        ];

        $minute = 10;

        $result = $class->getNextTime($hourList, $minute);

        $this->assertEquals(1437088200, $result);
    }

    public function testGetNextTimeNextDay()
    {
        $this->mockedMethodList = [
            'getCurrentTimeStamp'
        ];
        /** @var \PServerCore\Service\Timer|\PHPUnit_Framework_MockObject_MockObject $class */
        $class = $this->getClass();

        $class->expects($this->any())
            ->method('getCurrentTimeStamp')
            ->willReturn(1437082616);

        $hourList = [
            21,
            14
        ];

        $minute = 10;

        $result = $class->getNextTime($hourList, $minute);

        $this->assertEquals(1437142200, $result);
    }

    public function testGetNextTimeNextMonth()
    {
        $this->mockedMethodList = [
            'getCurrentTimeStamp'
        ];
        /** @var \PServerCore\Service\Timer|\PHPUnit_Framework_MockObject_MockObject $class */
        $class = $this->getClass();

        $class->expects($this->any())
            ->method('getCurrentTimeStamp')
            ->willReturn(1438373400);

        $hourList = [
            22,
            15
        ];

        $minute = 10;

        $result = $class->getNextTime($hourList, $minute);

        $this->assertEquals(1438380600, $result);
    }

    public function testGetNextTimeDay()
    {
        $this->mockedMethodList = [
            'getCurrentTimeStamp'
        ];
        /** @var \PServerCore\Service\Timer|\PHPUnit_Framework_MockObject_MockObject $class */
        $class = $this->getClass();

        $class->expects($this->any())
            ->method('getCurrentTimeStamp')
            ->willReturn(1437082616);

        $dayList = [
            'Wednesday',
            'Monday'
        ];

        $hour = 3;
        $minute = 0;
        $result = $class->getNextTimeDay($dayList, $hour, $minute);

        $this->assertEquals(1437361200, $result);
    }

    public function testGetCurrentTimeStamp()
    {
        /** @var \PServerCore\Service\Timer|\PHPUnit_Framework_MockObject_MockObject $class */
        $class = $this->getClass();
        $method = $this->getMethod('getCurrentTimeStamp');

        $result = $method->invokeArgs($class, []);

        $this->assertInternalType('integer', $result);
        $this->assertNotEmpty($result);
    }
}
