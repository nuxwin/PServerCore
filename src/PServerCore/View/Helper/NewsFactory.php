<?php


namespace PServerCore\View\Helper;


use Interop\Container\ContainerInterface;
use PServerCore\Service\News;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class NewsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return NewsWidget
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new NewsWidget(
            $container->get(News::class)
        );
    }

}