<?php

namespace PServerCore;

use PServerCore\Service\ServiceManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Factory\InvokableFactory;

class Module
{
    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        ServiceManager::setInstance($e->getApplication()->getServiceManager());
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'pserver_user_register_form' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    /** @noinspection PhpParamsInspection */
                    $form = new Form\Register(
                        $sm->get('Doctrine\ORM\EntityManager'),
                        $sm->get('SanCaptcha'),
                        $sm->get(Options\Collection::class)
                    );
                    /** @noinspection PhpParamsInspection */
                    $form->setInputFilter(
                        new Form\RegisterFilter(
                            $sm->get(Options\Collection::class),
                            $sm->get('Doctrine\ORM\EntityManager'),
                            $sm->get('gamebackend_dataservice')
                        )
                    );
                    return $form;
                },
                'pserver_user_password_form' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    /** @noinspection PhpParamsInspection */
                    $form = new Form\Password(
                        $sm->get('Doctrine\ORM\EntityManager'),
                        $sm->get(Options\Collection::class)
                    );
                    /** @noinspection PhpParamsInspection */
                    $form->setInputFilter(
                        new Form\PasswordFilter(
                            $sm->get('pserver_password_options'),
                            $sm->get('pserver_secret_question')
                        )
                    );
                    return $form;
                },
                'pserver_user_pwlost_form' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    /** @var $repositoryUser \Doctrine\Common\Persistence\ObjectRepository */
                    /** @var Options\EntityOptions $entityOptions */
                    $entityOptions = $sm->get('pserver_entity_options');
                    $repositoryUser = $sm->get(\Doctrine\ORM\EntityManager::class)->getRepository($entityOptions->getUser());
                    /** @noinspection PhpParamsInspection */
                    $form = new Form\PwLost($sm->get('SanCaptcha'));
                    $form->setInputFilter(
                        new Form\PwLostFilter(
                            new Validator\ValidUserExists($repositoryUser)
                        )
                    );
                    return $form;
                },
                'pserver_user_changepwd_form' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    $form = new Form\ChangePwd();
                    /** @noinspection PhpParamsInspection */
                    $form->setInputFilter(new Form\ChangePwdFilter($sm->get('pserver_password_options')));
                    return $form;
                },
                'pserver_user_add_mail_form' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    $form = new Form\AddEmail();
                    /** @noinspection PhpParamsInspection */
                    $form->setInputFilter(
                        new Form\AddEmailFilter(
                            $sm->get('Doctrine\ORM\EntityManager'),
                            $sm->get('Config')['pserver'],
                            $sm->get(Options\Collection::class)
                        )
                    );
                    return $form;
                },
                'pserver_entity_options' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    $config = $sm->get('Configuration');
                    return new Options\EntityOptions($config['pserver']['entity']);
                },
                'pserver_mail_options' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    $config = $sm->get('Configuration');
                    return new Options\MailOptions($config['pserver']['mail']);
                },
                'pserver_general_options' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    $config = $sm->get('Configuration');
                    return new Options\GeneralOptions($config['pserver']['general']);
                },
                'pserver_password_options' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    $config = $sm->get('Configuration');
                    return new Options\PasswordOptions($config['pserver']['password']);
                },
                'pserver_user_code_options' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    $config = $sm->get('Configuration');
                    return new Options\UserCodeOptions($config['pserver']['user_code']);
                },
                'pserver_login_options' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    $config = $sm->get('Configuration');
                    return new Options\LoginOptions($config['pserver']['login']);
                },
                'pserver_register_options' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    $config = $sm->get('Configuration');
                    return new Options\RegisterOptions($config['pserver']['register']);
                },
                'pserver_validation_options' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    $config = $sm->get('Configuration');
                    return new Options\ValidationOptions($config['pserver']['validation']);
                },
                'zfcticketsystem_ticketsystem_new_form' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    /** @noinspection PhpParamsInspection */
                    $form = new \ZfcTicketSystem\Form\TicketSystem(
                        $sm->get('Doctrine\ORM\EntityManager'),
                        $sm->get('zfcticketsystem_entry_options')
                    );

                    /** @noinspection PhpParamsInspection */
                    $form->setInputFilter(
                        new Form\TicketSystemFilter(
                            $sm->get('Doctrine\ORM\EntityManager'),
                            $sm->get('zfcticketsystem_entry_options'),
                            $sm->get('zfc-bbcode_parser')
                        )
                    );

                    return $form;
                },
                'zfcticketsystem_ticketsystem_entry_form' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    $form = new \ZfcTicketSystem\Form\TicketEntry();
                    /** @noinspection PhpParamsInspection */
                    $form->setInputFilter(new Form\TicketEntryFilter($sm->get('zfc-bbcode_parser')));
                    return $form;
                },
                'small_user_login_form' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    /** @var $repositoryUser \Doctrine\Common\Persistence\ObjectRepository */
                    /** @var Options\EntityOptions $entityOptions */
                    $entityOptions = $sm->get('pserver_entity_options');
                    $repositoryUser = $sm->get('Doctrine\ORM\EntityManager')->getRepository($entityOptions->getUser());
                    $form = new \SmallUser\Form\Login();
                    $form->setInputFilter(
                        new Form\LoginFilter(
                            new Validator\ValidUserExists($repositoryUser, 'NOT_ACTIVE')
                        )
                    );
                    return $form;
                },
            ],
        ];
    }

}
