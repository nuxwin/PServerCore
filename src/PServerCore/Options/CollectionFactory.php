<?php


namespace PServerCore\Options;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CollectionFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Collection
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $collection = new Collection();
        $collection->setEntityOptions($container->get(EntityOptions::class));
        $collection->setGeneralOptions($container->get(GeneralOptions::class));
        $collection->setLoginOptions($container->get(LoginOptions::class));
        $collection->setMailOptions($container->get(MailOptions::class));
        $collection->setPasswordOptions($container->get(PasswordOptions::class));
        $collection->setRegisterOptions($container->get(RegisterOptions::class));
        $collection->setUserCodesOptions($container->get(UserCodeOptions::class));
        $collection->setValidationOptions($container->get(ValidationOptions::class));
        $collection->setConfig($container->get('config')['pserver']);

        return $collection;
    }

}