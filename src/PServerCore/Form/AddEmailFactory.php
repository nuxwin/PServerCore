<?php

namespace PServerCore\Form;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PServerCore\Options;
use Zend\ServiceManager\Factory\FactoryInterface;

class AddEmailFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AddEmail
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $form = new AddEmail();
        $form->setInputFilter(
            new AddEmailFilter(
                $container->get(EntityManager::class),
                $container->get('config')['pserver'],
                $container->get(Options\Collection::class)
            )
        );
        return $form;
    }


}