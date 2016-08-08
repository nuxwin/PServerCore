<?php


namespace PServerCore\Controller;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class DonateFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return DonateController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DonateController(
            $container->get('small_user_service')
        );
    }

}