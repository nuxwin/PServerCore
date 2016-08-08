<?php


namespace PServerCore\Form;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PServerCore\Options\Collection;
use Zend\ServiceManager\Factory\FactoryInterface;

class PasswordFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Password
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $form = new Password(
            $container->get(EntityManager::class),
            $container->get(Collection::class)
        );

        $form->setInputFilter(
            new PasswordFilter(
                $container->get('pserver_password_options'),
                $container->get('pserver_secret_question')
            )
        );
        return $form;
    }

}