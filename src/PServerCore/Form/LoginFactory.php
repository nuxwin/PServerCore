<?php


namespace PServerCore\Form;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PServerCore\Options;
use PServerCore\Validator;
use SmallUser\Form\Login;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Login
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Options\EntityOptions $entityOptions */
        $entityOptions = $container->get('pserver_entity_options');
        $repositoryUser = $container->get(EntityManager::class)->getRepository($entityOptions->getUser());
        $form = new Login();
        $form->setInputFilter(
            new LoginFilter(
                new Validator\ValidUserExists($repositoryUser, 'NOT_ACTIVE')
            )
        );

        return $form;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Login
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, Login::class);
    }

}