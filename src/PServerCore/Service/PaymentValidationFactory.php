<?php


namespace PServerCore\Service;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class PaymentValidationFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PaymentValidation
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PaymentValidation(
            $container->get('small_user_service')
        );
    }

}