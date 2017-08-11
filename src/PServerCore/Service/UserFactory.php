<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PServerCore\Options\Collection;
use PServerCore\Service\User as ServiceUser;
use SmallUser\Service\UserFactory as SmallUserFactory;

class UserFactory extends SmallUserFactory
{
    /**
     * @var string
     */
    protected $className = ServiceUser::class;

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var User $class */
        $class = parent::__invoke($container, $requestedName, $options);

        $class->setEntityManager($container->get(EntityManager::class));
        $class->setCollectionOptions($container->get(Collection::class));
        $class->setIpService($container->get(Ip::class));
        $class->setMailService($container->get(Mail::class));
        $class->setUserCodeService($container->get(UserCodes::class));
        $class->setGameDataService($container->get('gamebackend_dataservice'));
        $class->setSecretQuestionService($container->get(SecretQuestion::class));
        $class->setUserBlockService($container->get(UserBlock::class));
        $class->setRegisterForm($container->get('pserver_user_register_form'));
        $class->setPasswordLostForm($container->get('pserver_user_pwlost_form'));
        $class->setPasswordForm($container->get('pserver_user_password_form'));

        return $class;
    }

}