<?php


namespace PServerCore\View\Helper;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class DateTimeFormatFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return DateTimeFormat
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DateTimeFormat(
            $container->get('pserver_general_options')
        );
    }

}