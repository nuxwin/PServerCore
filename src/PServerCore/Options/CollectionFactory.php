<?php


namespace PServerCore\Options;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CollectionFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Collection
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
        $collection = new Collection();
        /** @noinspection PhpParamsInspection */
        $collection->setEntityOptions($serviceLocator->get(EntityOptions::class));
        /** @noinspection PhpParamsInspection */
        $collection->setGeneralOptions($serviceLocator->get(GeneralOptions::class));
        /** @noinspection PhpParamsInspection */
        $collection->setLoginOptions($serviceLocator->get(LoginOptions::class));
        /** @noinspection PhpParamsInspection */
        $collection->setMailOptions($serviceLocator->get(MailOptions::class));
        /** @noinspection PhpParamsInspection */
        $collection->setPasswordOptions($serviceLocator->get(PasswordOptions::class));
        /** @noinspection PhpParamsInspection */
        $collection->setRegisterOptions($serviceLocator->get(RegisterOptions::class));
        /** @noinspection PhpParamsInspection */
        $collection->setUserCodesOptions($serviceLocator->get(UserCodeOptions::class));
        /** @noinspection PhpParamsInspection */
        $collection->setValidationOptions($serviceLocator->get(ValidationOptions::class));
        /** @noinspection PhpParamsInspection */
        $collection->setConfig($serviceLocator->get('config')['pserver']);

        return $collection;
    }

}