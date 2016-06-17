<?php


namespace PServerCore\Form;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ChangePwdFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ChangePwd
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $form = new ChangePwd();
        /** @noinspection PhpParamsInspection */
        $form->setInputFilter(new ChangePwdFilter($serviceLocator->get('pserver_password_options')));
        return $form;
    }

}