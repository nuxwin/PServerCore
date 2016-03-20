<?php


namespace PServerCoreTest\View\Helper;

use PServerCoreTest\Util\TestBase;

class TimerWidgetTest extends TestBase
{
    /** @var string */
    protected $className = 'PServerCore\View\Helper\TimerWidget';

    public function testInvoke()
    {
        $this->mockedMethodList = [
            'getView'
        ];

        /** @var \PServerCore\View\Helper\TimerWidget|\PHPUnit_Framework_MockObject_MockObject $class */
        $class = $this->getClass();

        $phpRenderer = $this->getMockBuilder('\Zend\View\Renderer\PhpRenderer')
            ->setMethods([])
            ->getMock();

        $class->expects($this->any())
            ->method('getView')
            ->willReturn($phpRenderer);

        $result = $class->__invoke();

        $this->assertNull($result);
    }
}
