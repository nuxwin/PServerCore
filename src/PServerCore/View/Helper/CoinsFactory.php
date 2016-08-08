<?php


namespace PServerCore\View\Helper;


use Interop\Container\ContainerInterface;
use PServerCore\Service\Coin;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class CoinsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return CoinsWidget
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CoinsWidget(
            $container->get('small_user_auth_service'),
            $container->get(Coin::class)
        );
    }

}