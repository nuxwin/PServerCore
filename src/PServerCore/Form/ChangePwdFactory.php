<?php


namespace PServerCore\Form;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ChangePwdFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ChangePwd
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $form = new ChangePwd();
        $form->setInputFilter(new ChangePwdFilter($container->get('pserver_password_options')));
        return $form;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ChangePwd
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, ChangePwd::class);
    }

}