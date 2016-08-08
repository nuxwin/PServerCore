<?php


namespace PServerCore\Form;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PServerCore\Options\Collection;
use Zend\ServiceManager\Factory\FactoryInterface;

class RegisterFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Register
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $form = new Register(
            $container->get(EntityManager::class),
            $container->get('SanCaptcha'),
            $container->get(Collection::class)
        );

        $form->setInputFilter(
            new RegisterFilter(
                $container->get(Collection::class),
                $container->get(EntityManager::class),
                $container->get('gamebackend_dataservice')
            )
        );

        return $form;
    }

}