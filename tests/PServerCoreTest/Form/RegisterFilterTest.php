<?php


namespace PServerCoreTest\Form;


use PServerCoreTest\Util\TestBase;

class RegisterFilterTest extends TestBase
{
    /** @var string */
    protected $className = '\PServerCore\Form\RegisterFilter';

    public function testIsValid()
    {
        $this->mockedMethodList = [
            'getUsernameValidator',
            'getUserNameBackendNotExistsValidator'
        ];

        $noRecordExistsMock = $this->getMockBuilder('PServerCore\Validator\NoRecordExists')
            ->disableOriginalConstructor()
            ->setMethods(['isValid'])
            ->getMock();

        $noRecordExistsMock->expects($this->any())
            ->method('isValid')
            ->willReturn(true);

        $UserNameBackendNotExistsMock = $this->getMockBuilder('PServerCore\Validator\UserNameBackendNotExists')
            ->disableOriginalConstructor()
            ->setMethods(['isValid'])
            ->getMock();

        $UserNameBackendNotExistsMock->expects($this->any())
            ->method('isValid')
            ->willReturn(true);

        $class = $this->getClass();

        $class->expects($this->any())
            ->method('getUsernameValidator')
            ->willReturn($noRecordExistsMock);

        $class->expects($this->any())
            ->method('getUserNameBackendNotExistsValidator')
            ->willReturn($UserNameBackendNotExistsMock);

        $class->__construct($this->serviceManager);

        $class->setData([
            'username' => 'fodfgo',
            'email' => 'fodfgo@example.com',
            'emailVerify' => 'fodfgo@example.com',
            'password' => 'fodfgo',
            'passwordVerify' => 'fodfgo',
        ]);

        $this->assertTrue($class->isValid());

        $class->setData([
            'username' => 'fo dfgo',
            'email' => 'fodfgo@example.com',
            'emailVerify' => 'fodfgo@example.com',
            'password' => 'fodfgo',
            'passwordVerify' => 'fodfgo',
        ]);

        $this->assertFalse($class->isValid());
    }

    /**
     * @return \PServerCore\Form\RegisterFilter|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getClass()
    {
        if (!$this->class) {
            /** @var \Zend\ServiceManager\ServiceManagerAwareInterface $class */
            $class = $this->getMockBuilder($this->className)
                ->disableOriginalConstructor()
                ->setMethods($this->getMockedMethodList())
                ->getMock();

            $this->class = $class;
        }

        return $this->class;
    }

}
