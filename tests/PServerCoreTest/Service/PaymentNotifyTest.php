<?php


namespace PServerCoreTest\Service;


use PaymentAPI\Provider\Request;
use PServerCoreTest\Util\TestBase;

class PaymentNotifyTest extends TestBase
{
    /** @var  string */
    protected $className = \PServerCore\Service\PaymentNotify::class;

    /**
     * @expectedException \Exception
     */
    public function testSuccessNoUser()
    {
        $this->mockedMethodList = [
            'getUser4Id'
        ];

        /** @var \PHPUnit_Framework_MockObject_MockObject|\PServerCore\Service\PaymentNotify $class */
        $class = $this->getClass();
        $class->expects($this->any())
            ->method('getUser4Id')
            ->willReturn(null);

        $request = new Request();

        $class->success($request);
    }
}
