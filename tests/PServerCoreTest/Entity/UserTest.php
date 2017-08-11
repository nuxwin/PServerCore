<?php

namespace PServerCoreTest\Entity;

use PServerCore\Entity\User;
use PServerCore\Entity\UserExtension;
use PServerCore\Entity\UserRole;
use PServerCoreTest\Util\TestBase;
use Zend\Crypt\Password\Bcrypt;

class UserTest extends TestBase
{
    public function testConstruct()
    {
        $entity = new User();

        $this->assertInstanceOf(\DateTime::class, $entity->getCreated());
        $this->assertInstanceOf(\PServerCore\Entity\UserInterface::class, $entity);
        $this->assertInstanceOf(\SmallUser\Entity\UserInterface::class, $entity);
        $this->assertInstanceOf(\BjyAuthorize\Provider\Role\ProviderInterface::class, $entity);
    }

    public function testUserId()
    {
        $entity = new User();
        $usrId = rand(0, 99999);
        $result = $entity->setId($usrId);

        $this->assertEquals($entity, $result);
        $this->assertEquals($usrId, $result->getId());
    }

    public function testUsername()
    {
        $entity = new User();
        $username = rand(0, 99999);
        $result = $entity->setUsername($username);

        $this->assertEquals($entity, $result);
        $this->assertEquals($username, $result->getUsername());
    }

    public function testBackendId()
    {
        $entity = new User();
        $backendId = rand(0, 99999);
        $result = $entity->setBackendId($backendId);

        $this->assertEquals($entity, $result);
        $this->assertEquals($backendId, $result->getBackendId());
    }

    public function testPassword()
    {
        $entity = new User();
        $password = rand(0, 99999);
        $result = $entity->setPassword($password);

        $this->assertEquals($entity, $result);
        $this->assertEquals($password, $result->getPassword());
    }

    public function testEmail()
    {
        $entity = new User();
        $email = 'foo@bar.baz';
        $result = $entity->setEmail($email);

        $this->assertEquals($entity, $result);
        $this->assertEquals($email, $result->getEmail());
    }

    public function testCreated()
    {
        $entity = new User();
        $dateTime = new \DateTime();
        $result = $entity->setCreated($dateTime);

        $this->assertEquals($entity, $result);
        $this->assertEquals($dateTime, $result->getCreated());
    }

    public function testCreatedIp()
    {
        $entity = new User();
        $createdIp = '127.0.0.45';
        $result = $entity->setCreateIp($createdIp);

        $this->assertEquals($entity, $result);
        $this->assertEquals($createdIp, $result->getCreateIp());
    }

    public function testAddUserRole()
    {
        $entity = new User();
        $entityRole = new UserRole();
        $result = $entity->addUserRole($entityRole);
        $this->assertEquals($entity, $result);

        $result = $entity->getUserRole();
        $this->assertInstanceOf(\Doctrine\Common\Collections\Collection::class, $result);
        $this->assertEquals($entityRole, $result[0]);
    }

    public function testRemoveUserRole()
    {
        $entity = new User();
        $entityRole = new UserRole();
        $entity->addUserRole($entityRole);

        $entity->removeUserRole($entityRole);

        $result = $entity->getUserRole();
        $this->assertInstanceOf(\Doctrine\Common\Collections\Collection::class, $result);
        $this->assertEmpty($result);
    }

    public function testGetRoles()
    {
        $entity = new User();
        $result = $entity->getRoles();
        $this->assertEmpty($result);

        $entityRole = new UserRole();
        $entity->addUserRole($entityRole);
        $result = $entity->getRoles();
        $this->assertInternalType('array', $result);
        $this->assertInstanceOf(\Zend\Permissions\Acl\Role\RoleInterface::class, $result[0]);
    }

    public function testUserExtension()
    {
        $entity = new User();
        $entityExtension = new UserExtension();

        $entity->addUserExtension($entityExtension);
        $entity->addUserExtension($entityExtension);

        $this->assertCount(2, $entity->getUserExtension());

        $entity->removeUserExtension($entityExtension);

        $this->assertCount(1, $entity->getUserExtension());
    }

    public function testHashPasswordTrue()
    {
        $this->markTestSkipped('TODO');
        $userService = $this->getMockBuilder(\PServerCore\Service\User::class)
            ->disableOriginalConstructor()
            ->setMethods(['isSamePasswordOption'])
            ->getMock();

        $userService->expects($this->any())
            ->method('isSamePasswordOption')
            ->will($this->returnValue(false));

        $this->serviceManager->setService('small_user_service', $userService);

        $entity = new User();
        $password = 'foobar';
        $bCrypt = new Bcrypt();

        $entity->setPassword($bCrypt->create($password));
        $result = User::hashPassword($entity, $password);

        $this->assertTrue($result);
    }

    public function testHashPasswordFalse()
    {
        $this->markTestSkipped('TODO');
        // We need a mocking of GameBackend =)
        $gameService = $this->getMockBuilder(\GameBackend\DataService\Mocking::class)
            ->disableOriginalConstructor()
            ->setMethods(['isPasswordSame'])
            ->getMock();

        $gameService->expects($this->any())
            ->method('isPasswordSame')
            ->will($this->returnValue(false));

        // Mock UserService
        $userService = $this->getMockBuilder(\PServerCore\Service\User::class)
            ->disableOriginalConstructor()
            ->setMethods(['isSamePasswordOption', 'getGameBackendService'])
            ->getMock();

        $userService->expects($this->any())
            ->method('isSamePasswordOption')
            ->will($this->returnValue(true));

        $userService->expects($this->any())
            ->method('getGameBackendService')
            ->will($this->returnValue($gameService));


        $this->serviceManager->setService('small_user_service', $userService);

        $entity = new User();
        $password = 'foobar';
        $bCrypt = new Bcrypt();

        $entity->setPassword($bCrypt->create($password));
        $result = User::hashPassword($entity, $password);

        $this->assertFalse($result);
    }


}
