<?php

namespace PServerCoreTest\View\Helper;

use PServerCoreTest\Util\TestBase;

class AgoTimerWidgetTest extends TestBase
{
    protected $className = \PServerCore\View\Helper\AgoTimerWidget::class;

    /**
     * @param string $modify
     * @param string $expected
     *
     * @dataProvider providerInvoke
     */
    public function testInvoke($modify, $expected)
    {
        /** @var \PServerCore\View\Helper\DateTimeFormat $class */
        $class = $this->getClass();

        $dateTime = new \DateTime();
        $dateTime->modify($modify);
        $result = $class->__invoke($dateTime);

        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function providerInvoke()
    {
        return [
            [
                '+10 hour',
                '-10:00:00'
            ],
            [
                '-2 hour 20minute 10seconds',
                '01:39:50'
            ],
            [
                '-1 day 2 hour 20minute 10seconds',
                '21:39:50'
            ],
            [
                '-2 hour',
                '02:00:00'
            ],
        ];
    }
}
