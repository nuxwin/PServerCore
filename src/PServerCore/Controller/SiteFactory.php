<?php


namespace PServerCore\Controller;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class SiteFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return SiteController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SiteController(
            $container->get('pserver_download_service'),
            $container->get('pserver_pageinfo_service')
        );
    }

}