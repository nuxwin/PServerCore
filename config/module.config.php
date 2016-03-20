<?php

use PServerCore\Controller;
use PServerCore\Entity;
use PServerCore\Options;
use PServerCore\Service;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'PServerCore' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => 'PServerCore\Controller\Index',
                        'action' => 'index',
                        'page' => 1
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'site-news' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => 'news-[:page].html',
                            'constraints' => [
                                'page' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => 'PServerCore\Controller\Index',
                                'action' => 'index',
                                'page' => 1
                            ],
                        ],
                    ],
                    'site-detail' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => 'detail-[:type].html',
                            'constraints' => [
                                'type' => '[a-zA-Z]+',
                            ],
                            'defaults' => [
                                'controller' => 'PServerCore\Controller\Site',
                                'action' => 'page'
                            ],
                        ],
                    ],
                    'site-download' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => 'download.html',
                            'defaults' => [
                                'controller' => 'PServerCore\Controller\Site',
                                'action' => 'download'
                            ],
                        ],
                    ],
                    'user' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => 'panel/account[/:action].html',
                            'constraints' => [
                                'action' => '[a-zA-Z-]+',
                            ],
                            'defaults' => [
                                'controller' => 'PServerCore\Controller\Account',
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'panel_donate' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => 'panel/donate[/:action].html',
                            'constraints' => [
                                'action' => '[a-zA-Z-]+',
                            ],
                            'defaults' => [
                                'controller' => 'PServerCore\Controller\Donate',
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'info' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => 'info[/:action].png',
                            'constraints' => [
                                'action' => '[a-zA-Z-]+',
                            ],
                            'defaults' => [
                                'controller' => 'PServerCore\Controller\Info',
                                'action' => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ],
        'aliases' => [
            'translator' => 'MvcTranslator',
            'payment_api_log_service' => Service\PaymentNotify::class,
            'zfcticketsystem_ticketsystem_service' => Service\TicketSystem::class,
            'payment_api_ip_service' => Service\Ip::class,
            'payment_api_validation' => Service\PaymentValidation::class,
            'pserver_options_collection' => Options\Collection::class,
            'pserver_usercodes_service' => Service\UserCodes::class,
            'pserver_timer_service' => Service\Timer::class,
            'pserver_cachinghelper_service' => Service\CachingHelper::class,
            'pserver_configread_service' => Service\ConfigRead::class,
            'pserver_donate_service' => Service\Donate::class,
            'pserver_format_service' => Service\Format::class,
            'pserver_login_history_service' => Service\LoginHistory::class,
            'pserver_log_service' => Service\Logs::class,
            'pserver_server_info_service' => Service\ServerInfo::class,
            'pserver_mail_service' => Service\Mail::class,
            'pserver_secret_question' => Service\SecretQuestion::class,
            'pserver_news_service' => Service\News::class,
            'pserver_download_service' => Service\Download::class,
            'pserver_add_email_service' => Service\AddEmail::class,
            'pserver_coin_service' => Service\Coin::class,
            'pserver_pageinfo_service' => Service\PageInfo::class,
            'pserver_playerhistory_service' => Service\PlayerHistory::class,
            'pserver_user_block_service' => Service\UserBlock::class,
            'pserver_user_role_service' => Service\UserRole::class,
            'small_user_service' => Service\User::class,
        ],
        'factories' => [
            'pserver_caching_service' => function () {
                $cache = \Zend\Cache\StorageFactory::factory([
                    'adapter' => 'filesystem',
                    'options' => [
                        'cache_dir' => __DIR__ . '/../../../../data/cache',
                        'ttl' => 86400
                    ],
                    'plugins' => [
                        'exception_handler' => [
                            'throw_exceptions' => false
                        ],
                        'serializer'
                    ],
                ]);
                return $cache;
            },
            Service\PaymentValidation::class => function ($sm) {
                /** @var $sm \Zend\Mvc\Controller\ControllerManager */
                /** @noinspection PhpParamsInspection */
                return new Service\PaymentValidation($sm->get('small_user_service'));
            },
            Service\TicketSystem::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                $ticketSystem = new Service\TicketSystem(
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get('zfcticketsystem_ticketsystem_new_form'),
                    $sm->get('zfcticketsystem_ticketsystem_entry_form'),
                    $sm->get('zfcticketsystem_entry_options')
                );

                /** @noinspection PhpParamsInspection */
                $ticketSystem->setMailService($sm->get('pserver_mail_service'));
                /** @noinspection PhpParamsInspection */
                $ticketSystem->setGeneralOptions($sm->get('pserver_general_options'));

                return $ticketSystem;
            },
            Service\PaymentValidation::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\PaymentValidation(
                    $sm->get('small_user_service')
                );
            },
            Options\Collection::class => Options\CollectionFactory::class,
            Service\Timer::class => InvokableFactory::class,
            Service\Ip::class => InvokableFactory::class,
            Service\UserCodes::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\UserCodes(
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get('pserver_format_service'),
                    $sm->get(Options\Collection::class)
                );
            },
            Service\CachingHelper::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\CachingHelper(
                    $sm->get('pserver_caching_service'),
                    $sm->get('pserver_general_options')
                );
            },
            Service\ConfigRead::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                return new Service\ConfigRead(
                    $sm->get('Config')
                );
            },
            Service\Donate::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\Donate(
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get('pserver_entity_options')
                );
            },
            Service\Format::class => InvokableFactory::class,
            Service\LoginHistory::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\LoginHistory(
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get('pserver_entity_options')
                );
            },
            Service\Logs::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\Logs(
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get('pserver_entity_options')
                );
            },
            Service\ServerInfo::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\ServerInfo(
                    $sm->get(Service\CachingHelper::class),
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get('pserver_entity_options'),
                    $sm->get('pserver_admin_server_info_form')
                );
            },
            Service\Mail::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\Mail(
                    $sm->get('ViewRenderer'),
                    $sm->get('pserver_options_collection'),
                    $sm->get('Doctrine\ORM\EntityManager')
                );
            },
            Service\SecretQuestion::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\SecretQuestion(
                    $sm->get('small_user_auth_service'),
                    $sm->get('ControllerPluginManager'),
                    $sm->get(Options\Collection::class),
                    $sm->get('pserver_user_add_mail_form'),
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get(Service\Mail::class),
                    $sm->get('pserver_admin_secret_question_form')
                );
            },
            Service\News::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\News(
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get('pserver_options_collection'),
                    $sm->get('pserver_admin_news_form')
                );
            },
            Service\Download::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\News(
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get('pserver_entity_options'),
                    $sm->get(Service\CachingHelper::class),
                    $sm->get('pserver_admin_download_form')
                );
            },
            Service\AddEmail::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\AddEmail(
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get('pserver_entity_options'),
                    $sm->get(Service\UserCodes::class)
                );
            },
            Service\Coin::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\Coin(
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get('gamebackend_dataservice'),
                    $sm->get('pserver_entity_options'),
                    $sm->get('pserver_admin_coin_form'),
                    $sm->get(Service\Ip::class)
                );
            },
            Service\PageInfo::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\PageInfo(
                    $sm->get(Service\CachingHelper::class),
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get(Options\Collection::class),
                    $sm->get('pserver_admin_page_info_form')
                );
            },
            Service\PlayerHistory::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\PlayerHistory(
                    $sm->get(Service\CachingHelper::class),
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get(Options\Collection::class),
                    $sm->get('gamebackend_dataservice')
                );
            },
            Service\UserBlock::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\UserBlock(
                    $sm->get('pserver_entity_options'),
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get('pserver_admin_user_block_form'),
                    $sm->get('gamebackend_dataservice')
                );
            },
            Service\UserRole::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\UserRole(
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get('pserver_admin_user_role_form'),
                    $sm->get('pserver_entity_options'),
                    $sm->get('ControllerPluginManager')
                );
            },
            Service\PaymentNotify::class => function ($sm) {
                /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
                /** @noinspection PhpParamsInspection */
                return new Service\PaymentNotify(
                    $sm->get('Doctrine\ORM\EntityManager'),
                    $sm->get(Options\Collection::class),
                    $sm->get(Service\Coin::class),
                    $sm->get(Service\UserBlock::class)
                );
            },
            Service\User::class => Service\UserFactory::class
        ],
    ],
    'controllers' => [
        'aliases' => [
            'PServerCore\Controller\Index' => Controller\IndexController::class,
            'SmallUser\Controller\Auth' => Controller\AuthController::class,
            'PServerCore\Controller\Auth' => Controller\AuthController::class,
            'PServerCore\Controller\Site' => Controller\SiteController::class,
            'PServerCore\Controller\Account' => Controller\AccountController::class,
            'PServerCore\Controller\Donate' => Controller\DonateController::class,
            'PServerCore\Controller\Info' => Controller\InfoController::class,
        ],
        'factories' => [
            Controller\IndexController::class => Controller\IndexFactory::class,
            Controller\AuthController::class => Controller\AuthFactory::class,
            Controller\SiteController::class => Controller\SiteFactory::class,
            Controller\AccountController::class => Controller\AccountFactory::class,
            Controller\DonateController::class => Controller\DonateFactory::class,
            Controller\InfoController::class => Controller\InfoFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.twig',
            'p-server-core/index/index' => __DIR__ . '/../view/p-server-core/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/403' => __DIR__ . '/../view/error/403.twig',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'email/tpl/register' => __DIR__ . '/../view/email/tpl/register.phtml',
            'email/tpl/password' => __DIR__ . '/../view/email/tpl/password.phtml',
            'email/tpl/country' => __DIR__ . '/../view/email/tpl/country.phtml',
            'email/tpl/secretLogin' => __DIR__ . '/../view/email/tpl/secret_login.phtml',
            'email/tpl/ticketAnswer' => __DIR__ . '/../view/email/tpl/ticket_answer.phtml',
            'email/tpl/addEmail' => __DIR__ . '/../view/email/tpl/add_email.phtml',
            'helper/sidebarWidget' => __DIR__ . '/../view/helper/sidebar.phtml',
            'helper/sidebarLoggedInWidget' => __DIR__ . '/../view/helper/logged-in.phtml',
            'helper/sidebarServerInfoWidget' => __DIR__ . '/../view/helper/server-info.phtml',
            'helper/formWidget' => __DIR__ . '/../view/helper/form.phtml',
            'helper/formNoLabelWidget' => __DIR__ . '/../view/helper/form-no-label.phtml',
            'helper/newsWidget' => __DIR__ . '/../view/helper/news-widget.phtml',
            'helper/sidebarTimerWidget' => __DIR__ . '/../view/helper/timer.phtml',
            'helper/playerHistory' => __DIR__ . '/../view/helper/player-history.phtml',
            'helper/sidebarLoginWidget' => __DIR__ . '/../view/helper/login-widget.phtml',
            'zfc-ticket-system/new' => __DIR__ . '/../view/zfc-ticket-system/ticket-system/new.twig',
            'zfc-ticket-system/view' => __DIR__ . '/../view/zfc-ticket-system/ticket-system/view.twig',
            'zfc-ticket-system/index' => __DIR__ . '/../view/zfc-ticket-system/ticket-system/index.twig',
            'small-user/login' => __DIR__ . '/../view/p-server-core/auth/login.twig',
            'small-user/logout-page' => __DIR__ . '/../view/p-server-core/auth/logout-page.twig',
            'p-server-core/paginator' => __DIR__ . '/../view/helper/paginator.phtml',
            'p-server-core/navigation' => __DIR__ . '/../view/helper/navigation.phtml',
        ],
        'template_path_stack' => [
            'p-server-core' => __DIR__ . '/../view',
        ],
    ],
    // Placeholder for console routes
    'console' => [
        'router' => [
            'routes' => [
            ],
        ],
    ],
    /**
     *  DB Connection-Setup
     */
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                // mssql db @ windows  => 'GameBackend\DBAL\Driver\PDOSqlsrv\Driver'
                // mssql db @ linux  => 'GameBackend\DBAL\Driver\PDODblib\Driver',
                'driverClass' => Doctrine\DBAL\Driver\PDOMySql\Driver::class,
                'params' => [
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => 'username',
                    'password' => 'password',
                    'dbname' => 'dbname',
                ],
                'doctrine_type_mappings' => [
                    'enum' => 'string'
                ],
            ],
            'orm_sro_account' => [
                // mssql db @ windows  => 'GameBackend\DBAL\Driver\PDOSqlsrv\Driver'
                // mssql db @ linux  => 'GameBackend\DBAL\Driver\PDODblib\Driver',
                'driverClass' => GameBackend\DBAL\Driver\PDODblib\Driver::class,
                'params' => [
                    'host' => 'local',
                    'port' => '1433',
                    'user' => 'foo',
                    'password' => 'bar',
                    'dbname' => 'ACCOUNT',
                ],
            ],
            'orm_sro_shard' => [
                // mssql db @ windows  => 'GameBackend\DBAL\Driver\PDOSqlsrv\Driver'
                // mssql db @ linux  => 'GameBackend\DBAL\Driver\PDODblib\Driver',
                'driverClass' => GameBackend\DBAL\Driver\PDODblib\Driver::class,
                'params' => [
                    'host' => 'local',
                    'port' => '1433',
                    'user' => 'foo',
                    'password' => 'bar',
                    'dbname' => 'SHARD',
                ],
            ],
            'orm_sro_log' => [
                // mssql db @ windows  => 'GameBackend\DBAL\Driver\PDOSqlsrv\Driver'
                // mssql db @ linux  => 'GameBackend\DBAL\Driver\PDODblib\Driver',
                'driverClass' => GameBackend\DBAL\Driver\PDODblib\Driver::class,
                'params' => [
                    'host' => 'local',
                    'port' => '1433',
                    'user' => 'foo',
                    'password' => 'bar',
                    'dbname' => 'LOG',
                ],
            ],
        ],
        'entitymanager' => [
            'orm_default' => [
                'connection' => 'orm_default',
                'configuration' => 'orm_default'
            ],
        ],
        'driver' => [
            'application_entities' => [
                'class' => Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/PServerCore/Entity'
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    'PServerCore\Entity' => 'application_entities',
                    'SmallUser\Entity' => null,
                    'ZfcTicketSystem\Entity' => null
                ],
            ],
        ],
    ],
    'pserver' => [
        'general' => [
            'datetime' => [
                'format' => [
                    'time' => 'Y-m-d H:i'
                ],
            ],
            'cache' => [
                'enable' => false
            ],
            'max_player' => 1000,
            'image_player' => [
                'font_color' => [
                    0,
                    0,
                    0
                ],
                'background_color' => [
                    237,
                    237,
                    237
                ],
            ],
            /**
             * send a mail to the ticket owner
             * after new entry in admin panel
             */
            'ticket_answer_mail' => false
        ],
        'register' => [
            /**
             * role after register
             */
            'role' => 'user',
            /**
             * mail confirmation after register?
             * WARNING for pw lost|country|ticket answer, we need a valid mail
             */
            'mail_confirmation' => false,
            /**
             * With that feature it is possible to add the user from the game-database to the web-database
             * Why we need a web-database with user information?
             * Easy reason the system support different games, and we have create a central interface for the login,
             * to add roles, create a history-log, 2 pw-system and and and
             */
            'dynamic-import' => true,
            /**
             * its allowed to use more as 1 time a email-address on different accounts
             * warning it can come to duplicate emails with the dynamic import feature
             */
            'duplicate_email' => true
        ],
        'mail' => [
            'from' => 'abcd@example.com',
            'from_name' => 'team',
            'subject' => [
                'register' => 'RegisterMail',
                'password' => 'LostPasswordMail',
                'country' => 'LoginIpMail',
                'secretLogin' => 'SecretLoginMail',
                'ticketAnswer' => 'TicketAnswer',
            ],
            'basic' => [
                'name' => 'localhost',
                'host' => 'smtp.example.com',
                'port' => 587,
                'connection_class' => 'login',
                'connection_config' => [
                    'username' => 'put your username',
                    'password' => 'put your password',
                    'ssl' => 'tls',
                ],
            ],
        ],
        'login' => [
            'exploit' => [
                'time' => 900, //in seconds
                'try' => 5
            ],
            /**
             * for more security we can check if the user login from a allowed country
             * WARNING YOU HAVE TO FILL THE "country_list" TABLE WITH IP COUNTRY MAPPING
             * That is the reason why its default disabled
             */
            'country_check' => false,
            /**
             * set the list of roles, which must confirm, there mail after login
             */
            'secret_login_role_list' => [],
        ],
        'password' => [
            /*
             * set other pw for web as ingame
             */
            'different_passwords' => true,
            /**
             * work with secret pw system, there is atm no admin view to handle the question =[
             */
            'secret_question' => false,
            /**
             * some games does not allowed so long password
             */
            'length' => [
                'min' => 6,
                'max' => 32
            ],
        ],
        'validation' => [
            'username' => [
                'length' => [
                    'min' => 3,
                    'max' => 16
                ],
            ],
        ],
        'user_code' => [
            'expire' => [
                /**
                 * null means we use the general value
                 */
                'general' => 86400,
                'register' => null,
                'password' => null,
                'country' => null,
                'add_email' => null,
                'secret_login' => 60
            ]
        ],
        'news' => [
            /**
             * limit of the news entries of the first page
             */
            'limit' => 5
        ],
        'pageinfotype' => [
            'faq',
            'rules',
            'guides',
            'events'
        ],
        'blacklisted' => [
            'email' => [
                /**
                 * example to block all emails ending with @foo.com and @bar.com, the @ will added automatic
                 * 'foo.com', 'bar.com'
                 */

            ],
        ],
        'entity' => [
            'available_countries' => Entity\AvailableCountries::class,
            'country_list' => Entity\CountryList::class,
            'donate_log' => Entity\DonateLog::class,
            'download_list' => Entity\DownloadList::class,
            'ip_block' => Entity\IpBlock::class,
            'login_failed' => Entity\LoginFailed::class,
            'login_history' => Entity\LoginHistory::class,
            'logs' => Entity\Logs::class,
            'news' => Entity\News::class,
            'page_info' => Entity\PageInfo::class,
            'player_history' => Entity\PlayerHistory::class,
            'secret_answer' => Entity\SecretAnswer::class,
            'secret_question' => Entity\SecretQuestion::class,
            'server_info' => Entity\ServerInfo::class,
            'user' => Entity\User::class,
            'user_block' => Entity\UserBlock::class,
            'user_codes' => Entity\UserCodes::class,
            'user_extension' => Entity\UserExtension::class,
            'user_role' => Entity\UserRole::class,
        ],
        'navigation' => [
            'home' => [
                'name' => 'Home',
                'route' => [
                    'name' => 'PServerCore',
                ],
            ],
            'download' => [
                'name' => 'Download',
                'route' => [
                    'name' => 'PServerCore/site-download',
                ],
            ],
            'ranking' => [
                'name' => 'Ranking',
                'route' => [
                    'name' => 'PServerRanking/ranking',
                ],
                'children' => [
                    '1_position' => [
                        'name' => 'TopPlayer',
                        'route' => [
                            'name' => 'PServerRanking/ranking',
                            'params' => [
                                'action' => 'top-player',
                            ],
                        ],
                    ],
                    '2_position' => [
                        'name' => 'TopGuild',
                        'route' => [
                            'name' => 'PServerRanking/ranking',
                            'params' => [
                                'action' => 'top-guild',
                            ],
                        ],
                    ],
                ],
            ],
            'server-info' => [
                'name' => 'ServerInfo',
                'route' => [
                    'name' => 'PServerCore/site-detail',
                ],
                'children' => [
                    '1_position' => [
                        'name' => 'FAQ',
                        'route' => [
                            'name' => 'PServerCore/site-detail',
                            'params' => [
                                'type' => 'faq',
                            ],
                        ],
                    ],
                    '2_position' => [
                        'name' => 'Rules',
                        'route' => [
                            'name' => 'PServerCore/site-detail',
                            'params' => [
                                'type' => 'rules',
                            ],
                        ],
                    ],
                    '3_position' => [
                        'name' => 'Guides',
                        'route' => [
                            'name' => 'PServerCore/site-detail',
                            'params' => [
                                'type' => 'guides',
                            ],
                        ],
                    ],
                    '4_position' => [
                        'name' => 'Events',
                        'route' => [
                            'name' => 'PServerCore/site-detail',
                            'params' => [
                                'type' => 'events',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'logged_in' => [
            'character' => [
                'name' => 'CharacterPanel',
                'route' => [
                    'name' => 'PServerPanel/character',
                ],
                'class' => 'fa fa-diamond'
            ],
            'account_panel' => [
                'name' => 'AccountPanel',
                'route' => [
                    'name' => 'PServerCore/user',
                ],
                'class' => 'glyphicon glyphicon-user'
            ],
            'ticket_system' => [
                'name' => 'TicketSystem',
                'route' => [
                    'name' => 'zfc-ticketsystem',
                ],
                'class' => 'fa fa-graduation-cap'
            ],
            'donate' => [
                'name' => 'Donate',
                'route' => [
                    'name' => 'PServerCore/panel_donate',
                ],
                'class' => 'fa fa-usd'
            ],
            'vote4coins' => [
                'name' => 'Vote4Coins',
                'route' => [
                    'name' => 'PServerPanel/vote',
                ],
                'class' => 'fa fa-gamepad'
            ],
            'admin_panel' => [
                'name' => 'AdminPanel',
                'route' => [
                    'name' => 'PServerAdmin/home',
                ],
                'class' => 'fa fa-graduation-cap'
            ],
        ],
        'payment-api' => [
            'ban-time' => '946681200',
        ],
    ],
    'authenticationadapter' => [
        'odm_default' => [
            'objectManager' => 'doctrine.documentmanager.odm_default',
            'identityClass' => Entity\User::class,
            'identityProperty' => 'username',
            'credentialProperty' => 'password',
            'credentialCallable' => 'PServerCore\Entity\User::hashPassword'
        ],
    ],
    'small-user' => [
        'user_entity' => [
            'class' => Entity\User::class
        ],
        'login' => [
            'route' => 'PServerCore'
        ],
    ],
    'payment-api' => [
        // more config params check https://github.com/kokspflanze/PaymentAPI/blob/master/config/module.config.php
        'payment-wall' => [
            /**
             * SecretKey
             */
            'secret-key' => '',
        ],
        'super-reward' => [
            /**
             * SecretKey
             */
            'secret-key' => ''
        ],
    ],
    'zfc-ticket-system' => [
        'entity' => [
            'ticket_category' => Entity\TicketSystem\TicketCategory::class,
            'ticket_entry' => Entity\TicketSystem\TicketEntry::class,
            'ticket_subject' => Entity\TicketSystem\TicketSubject::class,
            'user' => Entity\User::class,
        ],
    ],
    'ZfcDatagrid' => [
        'settings' => [
            'export' => [
                'enabled' => false,
            ],
        ],
    ],
];
