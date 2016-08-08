<?php


namespace PServerCore\Controller;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return IndexController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new IndexController(
            $container->get('pserver_news_service')
        );
    }
}