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
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getViewHelperConfig()
    {
        return [
            'aliases' => [
                'pserverformerrors' => View\Helper\FormError::class,
                'formlabel' => View\Helper\FormLabel::class,
                'formWidget' => View\Helper\FormWidget::class,
                'sidebarWidget' => View\Helper\SideBarWidget::class,
            ],
            'factories' => [
                View\Helper\FormError::class => InvokableFactory::class,
                View\Helper\FormLabel::class => InvokableFactory::class,
                View\Helper\FormWidget::class => InvokableFactory::class,
                View\Helper\SideBarWidget::class => InvokableFactory::class,
                'playerHistory' => function (AbstractPluginManager $pluginManager) {
                    /** @noinspection PhpParamsInspection */
                    return new View\Helper\PlayerHistory(
                        $pluginManager->getServiceLocator()->get('pserver_playerhistory_service'),
                        $pluginManager->getServiceLocator()->get('pserver_general_options')

                    );
                },
                'active' => function (AbstractPluginManager $pluginManager) {
                    /** @noinspection PhpParamsInspection */
                    return new View\Helper\Active(
                        $pluginManager->getServiceLocator()->get('router'),
                        $pluginManager->getServiceLocator()->get('request')
                    );
                },
                'donateSum' => function (AbstractPluginManager $pluginManager) {
                    /** @noinspection PhpParamsInspection */
                    return new View\Helper\DonateSum(
                        $pluginManager->getServiceLocator()->get('pserver_donate_service')
                    );
                },
                'donateCounter' => function (AbstractPluginManager $pluginManager) {
                    /** @noinspection PhpParamsInspection */
                    return new View\Helper\DonateCounter(
                        $pluginManager->getServiceLocator()->get('pserver_donate_service')
                    );
                },
                'navigationWidgetPServerCore' => function (AbstractPluginManager $pluginManager) {
                    return new View\Helper\NavigationWidget(
                        $pluginManager->getServiceLocator()->get('Config')['pserver']
                    );
                },
                'dateTimeFormatTime' => function (AbstractPluginManager $pluginManager) {
                    /** @noinspection PhpParamsInspection */
                    return new View\Helper\DateTimeFormat(
                        $pluginManager->getServiceLocator()->get('pserver_general_options')
                    );
                },
                'newsWidget' => function (AbstractPluginManager $pluginManager) {
                    /** @noinspection PhpParamsInspection */
                    return new View\Helper\NewsWidget(
                        $pluginManager->getServiceLocator()->get('pserver_news_service')
                    );
                },
                'loggedInWidgetPServerCore' => function (AbstractPluginManager $pluginManager) {
                    /** @noinspection PhpParamsInspection */
                    return new View\Helper\LoggedInWidget(
                        $pluginManager->getServiceLocator()->get('small_user_auth_service'),
                        $pluginManager->getServiceLocator()->get('Config')['pserver'],
                        $pluginManager->getServiceLocator()->get('gamebackend_dataservice')
                    );
                },
                'loginWidgetPServerCore' => function (AbstractPluginManager $pluginManager) {
                    /** @noinspection PhpParamsInspection */
                    return new View\Helper\LoginWidget($pluginManager->getServiceLocator()->get('small_user_service'));
                },
                'serverInfoWidgetPServerCore' => function (AbstractPluginManager $pluginManager) {
                    /** @noinspection PhpParamsInspection */
                    return new View\Helper\ServerInfoWidget(
                        $pluginManager->getServiceLocator()->get('pserver_server_info_service')
                    );
                },
                'timerWidgetPServerCore' => function (AbstractPluginManager $pluginManager) {
                    /** @noinspection PhpParamsInspection */
                    return new View\Helper\TimerWidget(
                        $pluginManager->getServiceLocator()->get('Config')['pserver'],
                        $pluginManager->getServiceLocator()->get(Service\Timer::class)
                    );
                },
                'coinsWidgetPServerCore' => function (AbstractPluginManager $pluginManager) {
                    /** @noinspection PhpParamsInspection */
                    return new View\Helper\CoinsWidget(
                        $pluginManager->getServiceLocator()->get('small_user_auth_service'),
                        $pluginManager->getServiceLocator()->get('pserver_coin_service')
                    );
                },
            ]
        ];
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
                    $form = new Form\Register($sm);
                    $form->setInputFilter(new Form\RegisterFilter($sm));
                    return $form;
                },
                'pserver_user_password_form' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    $form = new Form\Password($sm);
                    $form->setInputFilter(
                        new Form\PasswordFilter($sm)
                    );
                    return $form;
                },
                'pserver_user_pwlost_form' => function ($sm) {
                    /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                    /** @var $repositoryUser \Doctrine\Common\Persistence\ObjectRepository */
                    /** @var Options\EntityOptions $entityOptions */
                    $entityOptions = $sm->get('pserver_entity_options');
                    $repositoryUser = $sm->get('Doctrine\ORM\EntityManager')->getRepository($entityOptions->getUser());
                    $form = new Form\PwLost($sm);
                    $form->setInputFilter(
                        new Form\PwLostFilter(
                            new Validator\ValidUserExists($repositoryUser)
                        )
                    );
                    return $form;
                },
                'pserver_user_changepwd_form' => function ($sm) {
                    $form = new Form\ChangePwd();
                    $form->setInputFilter(new Form\ChangePwdFilter($sm));
                    return $form;
                },
                'pserver_user_add_mail_form' => function ($sm) {
                    $form = new Form\AddEmail();
                    $form->setInputFilter(new Form\AddEmailFilter($sm));
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
                    $form->setInputFilter(new Form\TicketEntryFilter($sm));
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
