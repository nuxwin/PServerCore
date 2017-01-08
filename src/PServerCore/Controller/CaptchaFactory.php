<?php

namespace PServerCore\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CaptchaFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return CaptchaController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CaptchaController($container->get('SanCaptcha'));
    }

}