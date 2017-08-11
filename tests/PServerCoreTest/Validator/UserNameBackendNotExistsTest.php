<?php

namespace PServerCoreTest\Validator;

use PServerCore\Options\EntityOptions;
use PServerCoreTest\Util\TestBase;

class UserNameBackendNotExistsTest extends TestBase
{
    /** @var string */
    protected $className = \PServerCore\Validator\UserNameBackendNotExists::class;

    public function testIsValid()
    {
        $gameMock = $this->getMockBuilder(\GameBackend\DataService\Mocking::class)
            ->disableOriginalConstructor()
            ->setMethods(['isUserNameExists'])
            ->getMock();

        $gameMock->expects($this->any())
            ->method('isUserNameExists')
            ->willReturn(false);

        $this->mockedConstructorArgList = [
            $gameMock,
            new EntityOptions()
        ];
        $class = $this->getClass();

        $result = $class->isValid('foobar');
        $this->assertTrue($result);
        $this->assertEmpty($class->getMessages());
    }

    public function testIsValidFalse()
    {
        $gameMock = $this->getMockBuilder(\GameBackend\DataService\Mocking::class)
            ->disableOriginalConstructor()
            ->setMethods(['isUserNameExists'])
            ->getMock();

        $gameMock->expects($this->any())
            ->method('isUserNameExists')
            ->willReturn(true);

        $this->mockedConstructorArgList = [
            $gameMock,
            new EntityOptions()
        ];
        $class = $this->getClass();

        $result = $class->isValid('foobar');

        $this->assertFalse($result);
        $this->assertNotEmpty($class->getMessages());
    }


}
