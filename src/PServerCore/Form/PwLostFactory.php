<?php


namespace PServerCore\Form;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PServerCore\Options;
use PServerCore\Validator\ValidUserExists;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PwLostFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PwLost
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var $repositoryUser \Doctrine\Common\Persistence\ObjectRepository */
        /** @var Options\EntityOptions $entityOptions */
        $entityOptions = $container->get('pserver_entity_options');
        $repositoryUser = $container->get(EntityManager::class)->getRepository($entityOptions->getUser());
        $form = new PwLost($container->get('SanCaptcha'));
        $form->setInputFilter(
            new PwLostFilter(
                new ValidUserExists($repositoryUser)
            )
        );

        return $form;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return PwLost
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, PwLost::class);
    }

}