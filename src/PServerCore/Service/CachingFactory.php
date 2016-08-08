<?php


namespace PServerCore\Service;


use Interop\Container\ContainerInterface;
use Zend\Cache\StorageFactory;
use Zend\ServiceManager\Factory\FactoryInterface;

class CachingFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return \Zend\Cache\Storage\StorageInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return StorageFactory::factory([
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