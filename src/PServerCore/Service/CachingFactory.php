<?php


namespace PServerCore\Service;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CachingFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Zend\Cache\Storage\StorageInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return \Zend\Cache\StorageFactory::factory([
            'adapter' => 'filesystem',
            'options' => [
                'cache_dir' => __DIR__ . '/../../../../../../data/cache',
                'ttl' => 86400
            ],
            'plugins' => [
                'exception_handler' => [
                    'throw_exceptions' => false
                ],
                'serializer'
            ],
        ]);
    }

}