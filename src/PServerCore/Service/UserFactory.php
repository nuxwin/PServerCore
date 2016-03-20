<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use PServerCore\Options\Collection;
use SmallUser\Service\UserFactory as SmallUserFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserFactory extends SmallUserFactory
{
    protected $className = \PServerCore\Service\User::class;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return User
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var User $class */
        $class = parent::createService($serviceLocator);

        /** @noinspection PhpParamsInspection */
        $class->setEntityManager($serviceLocator->get(EntityManager::class));
        /** @noinspection PhpParamsInspection */
        $class->setCollectionOptions($serviceLocator->get(Collection::class));
        /** @noinspection PhpParamsInspection */
        $class->setIpService($serviceLocator->get(Ip::class));
        /** @noinspection PhpParamsInspection */
        $class->setMailService($serviceLocator->get(Mail::class));
        /** @noinspection PhpParamsInspection */
        $class->setUserCodeService($serviceLocator->get(UserCodes::class));
        /** @noinspection PhpParamsInspection */
        $class->setGameDataService($serviceLocator->get('gamebackend_dataservice'));
        /** @noinspection PhpParamsInspection */
        $class->setSecretQuestionService($serviceLocator->get(SecretQuestion::class));
        /** @noinspection PhpParamsInspection */
        $class->setUserBlockService($serviceLocator->get(UserBlock::class));
        /** @noinspection PhpParamsInspection */
        $class->setRegisterForm($serviceLocator->get('pserver_user_register_form'));
        /** @noinspection PhpParamsInspection */
        $class->setPasswordLostForm($serviceLocator->get('pserver_user_pwlost_form'));
        /** @noinspection PhpParamsInspection */
        $class->setChangePasswordForm($serviceLocator->get('pserver_user_changepwd_form'));
        /** @noinspection PhpParamsInspection */
        $class->setPasswordForm($serviceLocator->get('pserver_user_password_form'));

        return $class;
    }

}