<?php


namespace PServerCore\Form;


use Doctrine\ORM\EntityManager;
use PServerCore\Options;
use PServerCore\Validator\ValidUserExists;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PwLostFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var $repositoryUser \Doctrine\Common\Persistence\ObjectRepository */
        /** @var Options\EntityOptions $entityOptions */
        $entityOptions = $serviceLocator->get('pserver_entity_options');
        $repositoryUser = $serviceLocator->get(EntityManager::class)->getRepository($entityOptions->getUser());
        /** @noinspection PhpParamsInspection */
        $form = new PwLost($serviceLocator->get('SanCaptcha'));
        $form->setInputFilter(
            new PwLostFilter(
                new ValidUserExists($repositoryUser)
            )
        );
        return $form;
    }

}