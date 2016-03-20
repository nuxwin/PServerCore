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
        $collection->setEntityOptions($serviceLocator->get('pserver_entity_options'));
        /** @noinspection PhpParamsInspection */
        $collection->setGeneralOptions($serviceLocator->get('pserver_general_options'));
        /** @noinspection PhpParamsInspection */
        $collection->setLoginOptions($serviceLocator->get('pserver_login_options'));
        /** @noinspection PhpParamsInspection */
        $collection->setMailOptions($serviceLocator->get('pserver_mail_options'));
        /** @noinspection PhpParamsInspection */
        $collection->setPasswordOptions($serviceLocator->get('pserver_password_options'));
        /** @noinspection PhpParamsInspection */
        $collection->setRegisterOptions($serviceLocator->get('pserver_register_options'));
        /** @noinspection PhpParamsInspection */
        $collection->setUserCodesOptions($serviceLocator->get('pserver_user_code_options'));
        /** @noinspection PhpParamsInspection */
        $collection->setValidationOptions($serviceLocator->get('pserver_validation_options'));
        /** @noinspection PhpParamsInspection */
        $collection->setConfig($serviceLocator->get('Config')['pserver']);

        return $collection;
    }

}