<?php


namespace PServerCoreTest\View\Helper;


use PServerCore\Options\GeneralOptions;
use PServerCoreTest\Util\TestBase;

class DateTimeFormatTest extends TestBase
{
    protected $className = 'PServerCore\View\Helper\DateTimeFormat';

    public function testInvoke()
    {
        $config = [
            'format' => [
                'time' => 'Y-m-d'
            ],
        ];

        $this->mockedConstructorArgList = [(new GeneralOptions($config))->setDatetime($config)];

        /** @var \PServerCore\View\Helper\DateTimeFormat $class */
        $class = $this->getClass();

        $dateTime = new \DateTime();
        $dateTime->setDate(2000, 1, 1);
        $result = $class->__invoke($dateTime);

        $this->assertEquals('2000-01-01', $result);
    }
}
