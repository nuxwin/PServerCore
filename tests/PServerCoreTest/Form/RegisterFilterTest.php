<?php


namespace PServerCoreTest\Form;


use Doctrine\ORM\EntityManager;
use GameBackend\DataService\Mocking;
use PServerCore\Options\Collection;
use PServerCore\Options\PasswordOptions;
use PServerCore\Options\RegisterOptions;
use PServerCore\Options\ValidationOptions;
use PServerCore\Validator\NoRecordExists;
use PServerCore\Validator\UserNameBackendNotExists;
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

        $validationOptions = $this->getMockBuilder(ValidationOptions::class)
            ->setMethods(null)
            ->getMock();

        $registerOptions = $this->getMockBuilder(RegisterOptions::class)
            ->setMethods(null)
            ->getMock();

        $passwordOptions = $this->getMockBuilder(PasswordOptions::class)
            ->setMethods(null)
            ->getMock();

        /** @var \PHPUnit_Framework_MockObject_MockObject|Collection $collection */
        $collection = $this->getMockBuilder(Collection::class)
            ->setMethods(['getValidationOptions', 'getRegisterOptions', 'getPasswordOptions'])
            ->getMock();

        $collection->expects($this->any())
            ->method('getValidationOptions')
            ->willReturn($validationOptions);

        $collection->expects($this->any())
            ->method('getRegisterOptions')
            ->willReturn($registerOptions);

        $collection->expects($this->any())
            ->method('getPasswordOptions')
            ->willReturn($passwordOptions);

        $collection->setConfig([
            'blacklisted' => [
                'email' => [

                ],
            ],
        ]);


        $noRecordExistsMock = $this->getMockBuilder(NoRecordExists::class)
            ->disableOriginalConstructor()
            ->setMethods(['isValid'])
            ->getMock();

        $noRecordExistsMock->expects($this->any())
            ->method('isValid')
            ->willReturn(true);

        $UserNameBackendNotExistsMock = $this->getMockBuilder(UserNameBackendNotExists::class)
            ->disableOriginalConstructor()
            ->setMethods(['isValid'])
            ->getMock();

        $UserNameBackendNotExistsMock->expects($this->any())
            ->method('isValid')
            ->willReturn(true);

        /** @var \PHPUnit_Framework_MockObject_MockObject|\PServerCore\Form\RegisterFilter $class */
        $class = $this->getClass();

        $class->expects($this->any())
            ->method('getUsernameValidator')
            ->willReturn($noRecordExistsMock);

        $class->expects($this->any())
            ->method('getUserNameBackendNotExistsValidator')
            ->willReturn($UserNameBackendNotExistsMock);

        /** @noinspection PhpParamsInspection */
        $class->__construct(
            $collection,
            $this->getMockBuilder(EntityManager::class)
                ->disableOriginalConstructor()
                ->getMock(),
            $this->getMockBuilder(Mocking::class)
                ->disableOriginalConstructor()
                ->getMock()
        );

        $class->setData([
            'username' => 'fo dfgo',
            'email' => 'fodfgo@example.com',
            'emailVerify' => 'fodfgo@example.com',
            'password' => 'fodfgo',
            'passwordVerify' => 'fodfgo',
        ]);

        $this->assertFalse($class->isValid());
    }


}
