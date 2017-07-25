<?php

use PServerCore\Controller;
use PServerCore\Entity;
use PServerCore\Guard;
use PServerCore\Options;
use PServerCore\View\Helper;
use PServerCore\Form;
use PServerCore\Service;
use Zend\Navigation\Service\NavigationAbstractServiceFactory;
use Zend\Router\Http;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'PServerCore' => [
                'type' => Http\Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                        'page' => 1
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'site-news' => [
                        'type' => Http\Segment::class,
                        'options' => [
                            'route' => 'news-[:page].html',
                            'constraints' => [
                                'page' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'index',
                                'page' => 1
                            ],
                        ],
                    ],
                    'site-detail' => [
                        'type' => Http\Segment::class,
                        'options' => [
                            'route' => 'detail-[:type].html',
                            'constraints' => [
                                'type' => '[a-zA-Z]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\SiteController::class,
                                'action' => 'page'
                            ],
                        ],
                    ],
                    'site-download' => [
                        'type' => Http\Segment::class,
                        'options' => [
                            'route' => 'download.html',
                            'defaults' => [
                                'controller' => Controller\SiteController::class,
                                'action' => 'download'
                            ],
                        ],
                    ],
                    'user' => [
                        'type' => Http\Segment::class,
                        'options' => [
                            'route' => 'panel/account[/:action].html',
                            'constraints' => [
                                'action' => '[a-zA-Z-]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\AccountController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'panel_donate' => [
                        'type' => Http\Segment::class,
                        'options' => [
                            'route' => 'panel/donate[/:action].html',
                            'constraints' => [
                                'action' => '[a-zA-Z-]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\DonateController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'info' => [
                        'type' => Http\Segment::class,
                        'options' => [
                            'route' => 'info[/:action].png',
                            'constraints' => [
                                'action' => '[a-zA-Z-]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\InfoController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'captcha' => [
                        'type' => Http\Segment::class,
                        'options' => [
                            'route' => 'security/captcha[/:action].html',
                            'constraints' => [
                                'action' => '[a-zA-Z-]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\CaptchaController::class,
                            ],
                        ],
                    ],
                    'coins' => [
                        'type' => Http\Segment::class,
                        'options' => [
                            'route' => 'panel/coins[/:action].html',
                            'constraints' => [
                                'action' => '[a-zA-Z-]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\CoinsController::class,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            \Zend\Cache\Service\StorageCacheAbstractServiceFactory::class,
            \Zend\Log\LoggerAbstractServiceFactory::class,
            NavigationAbstractServiceFactory::class => NavigationAbstractServiceFactory::class,
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
            'pserver_user_register_form' => Form\Register::class,
            'pserver_user_password_form' => Form\Password::class,
            'pserver_user_pwlost_form' => Form\PwLost::class,
            'pserver_user_changepwd_form' => Form\ChangePwd::class,
            'pserver_user_add_mail_form' => Form\AddEmail::class,
            'zfcticketsystem_ticketsystem_new_form' => \ZfcTicketSystem\Form\TicketSystem::class,
            'zfcticketsystem_ticketsystem_entry_form' => \ZfcTicketSystem\Form\TicketEntry::class,
            'small_user_login_form' => \SmallUser\Form\Login::class,
            'pserver_entity_options' => Options\EntityOptions::class,
            'pserver_mail_options' => Options\MailOptions::class,
            'pserver_general_options' => Options\GeneralOptions::class,
            'pserver_password_options' => Options\PasswordOptions::class,
            'pserver_user_code_options' => Options\UserCodeOptions::class,
            'pserver_login_options' => Options\LoginOptions::class,
            'pserver_register_options' => Options\RegisterOptions::class,
            'pserver_validation_options' => Options\ValidationOptions::class,
        ],
        'factories' => [
            'pserver_caching_service' => Service\CachingFactory::class,
            Service\Account::class => Service\AccountFactory::class,
            Service\TicketSystem::class => Service\TicketSystemFactory::class,
            Service\PaymentValidation::class => Service\PaymentValidationFactory::class,
            Options\Collection::class => Options\CollectionFactory::class,
            Service\Timer::class => InvokableFactory::class,
            Service\Ip::class => InvokableFactory::class,
            Service\UserCodes::class => Service\UserCodesFactory::class,
            Service\CachingHelper::class => Service\CachingHelperFactory::class,
            Service\ConfigRead::class => Service\ConfigReadFactory::class,
            Service\Donate::class => Service\DonateFactory::class,
            Service\Format::class => InvokableFactory::class,
            Service\LoginHistory::class => Service\LoginHistoryFactory::class,
            Service\Logs::class => Service\LogsFactory::class,
            Service\ServerInfo::class => Service\ServerInfoFactory::class,
            Service\Mail::class => Service\MailFactory::class,
            Service\SecretQuestion::class => Service\SecretQuestionFactory::class,
            Service\News::class => Service\NewsFactory::class,
            Service\Download::class => Service\DownloadFactory::class,
            Service\AddEmail::class => Service\AddEmailFactory::class,
            Service\Coin::class => Service\CoinFactory::class,
            Service\PageInfo::class => Service\PageInfoFactory::class,
            Service\PlayerHistory::class => Service\PlayerHistoryFactory::class,
            Service\UserBlock::class => Service\UserBlockFactory::class,
            Service\UserRole::class => Service\UserRoleFactory::class,
            Service\PaymentNotify::class => Service\PaymentNotifyFactory::class,
            Service\PaymentNotifyCoins::class => InvokableFactory::class,
            \PaymentAPI\Service\Log::class => Service\PaymentNotifyFactory::class,
            Service\User::class => Service\UserFactory::class,
            Form\Register::class => Form\RegisterFactory::class,
            Form\Password::class => Form\PasswordFactory::class,
            Form\PwLost::class => Form\PwLostFactory::class,
            Form\ChangePwd::class => Form\ChangePwdFactory::class,
            Form\AddEmail::class => Form\AddEmailFactory::class,
            \ZfcTicketSystem\Form\TicketSystem::class => Form\TicketSystemFactory::class,
            \ZfcTicketSystem\Form\TicketEntry::class => Form\TicketEntryFactory::class,
            \SmallUser\Form\Login::class => Form\LoginFactory::class,
            Options\EntityOptions::class => Options\EntityOptionsFactory::class,
            Options\MailOptions::class => Options\MailOptionsFactory::class,
            Options\GeneralOptions::class => Options\GeneralOptionsFactory::class,
            Options\PasswordOptions::class => Options\PasswordOptionsFactory::class,
            Options\UserCodeOptions::class => Options\UserCodeOptionsFactory::class,
            Options\LoginOptions::class => Options\LoginOptionsFactory::class,
            Options\RegisterOptions::class => Options\RegisterOptionsFactory::class,
            Options\ValidationOptions::class => Options\ValidationOptionsFactory::class,
            Guard\UserBlock::class => Guard\UserBlockFactory::class,
            Guard\UserRefresh::class => Guard\UserRefreshFactory::class,
        ],
    ],
    'controllers' => [
        'aliases' => [
            'SmallUser\Controller\Auth' => Controller\AuthController::class,
        ],
        'factories' => [
            Controller\IndexController::class => Controller\IndexFactory::class,
            Controller\AuthController::class => Controller\AuthFactory::class,
            Controller\SiteController::class => Controller\SiteFactory::class,
            Controller\AccountController::class => Controller\AccountFactory::class,
            Controller\DonateController::class => Controller\DonateFactory::class,
            Controller\InfoController::class => Controller\InfoFactory::class,
            Controller\CaptchaController::class => Controller\CaptchaFactory::class,
            Controller\CoinsController::class => Controller\CoinsFactory::class,
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'pserverformerrors' => Helper\FormError::class,
            'formlabel' => Helper\FormLabel::class,
            'formWidget' => Helper\FormWidget::class,
            'sidebarWidget' => Helper\SideBarWidget::class,
            'playerHistory' => Helper\PlayerHistory::class,
            'active' => Helper\Active::class,
            'donateSum' => Helper\DonateSum::class,
            'donateCounter' => Helper\DonateCounter::class,
            'navigationWidgetPServerCore' => Helper\NavigationWidget::class,
            'dateTimeFormatTime' => Helper\DateTimeFormat::class,
            'newsWidget' => Helper\NewsWidget::class,
            'loggedInWidgetPServerCore' => Helper\LoggedInWidget::class,
            'loginWidgetPServerCore' => Helper\LoginWidget::class,
            'serverInfoWidgetPServerCore' => Helper\ServerInfoWidget::class,
            'timerWidgetPServerCore' => Helper\TimerWidget::class,
            'coinsWidgetPServerCore' => Helper\CoinsWidget::class,
            'captcha/image' => Helper\CaptchaImageReload::class, // overwrite
            'coinsInfoWidgetPServerCore' => Helper\CoinsInfoWidget::class,
            'agoTimerPServerCore' => Helper\AgoTimerWidget::class,
        ],
        'factories' => [
            Helper\FormError::class => InvokableFactory::class,
            Helper\FormLabel::class => InvokableFactory::class,
            Helper\FormWidget::class => InvokableFactory::class,
            Helper\SideBarWidget::class => InvokableFactory::class,
            Helper\PlayerHistory::class => Helper\PlayerHistoryFactory::class,
            Helper\Active::class => Helper\ActiveFactory::class,
            Helper\DonateSum::class => Helper\DonateSumFactory::class,
            Helper\DonateCounter::class => Helper\DonateCounterFactory::class,
            Helper\NavigationWidget::class => Helper\NavigationWidgetFactory::class,
            Helper\DateTimeFormat::class => Helper\DateTimeFormatFactory::class,
            Helper\NewsWidget::class => Helper\NewsFactory::class,
            Helper\LoggedInWidget::class => Helper\LoggedInFactory::class,
            Helper\LoginWidget::class => Helper\LoginFactory::class,
            Helper\ServerInfoWidget::class => Helper\ServerInfoFactory::class,
            Helper\TimerWidget::class => Helper\TimerFactory::class,
            Helper\CoinsWidget::class => Helper\CoinsFactory::class,
            Helper\CaptchaImageReload::class => InvokableFactory::class,
            Helper\CoinsInfoWidget::class => InvokableFactory::class,
            Helper\AgoTimerWidget::class => InvokableFactory::class,
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
            'p-server-core/index/index' => __DIR__ . '/../view/p-server-core/index/index.twig',
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
            'p-server-core/account-navigation' => __DIR__ . '/../view/helper/account-navigation.phtml',
            'p-server-core/footer-navigation' => __DIR__ . '/../view/helper/footer-navigation.phtml',
            'helper/captcha-image-reload' => __DIR__ . '/../view/helper/captcha-image-reload.phtml',
            'helper/coins-info-widget' => __DIR__ . '/../view/helper/coins-info-widget.phtml',
        ],
        'template_path_stack' => [
            'p-server-core' => __DIR__ . '/../view',
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
                'driverClass' => \Doctrine\DBAL\Driver\PDOMySql\Driver::class,
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
                'driverClass' => \GameBackend\DBAL\Driver\PDODblib\Driver::class,
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
                'driverClass' => \GameBackend\DBAL\Driver\PDODblib\Driver::class,
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
                'driverClass' => \GameBackend\DBAL\Driver\PDODblib\Driver::class,
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
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
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
                'enable' => true,
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
            /**
             * that log each mail in the web-log
             */
            'debug' => false,
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
            /**
             * password must contain a number
             */
            'contains_number' => false,
            /**
             * password must contain a lower letter
             */
            'contains_lower_letter' => false,
            /**
             * password must contain a upper letter
             */
            'contains_upper_letter' => false,
            /**
             * password must contain a special char
             */
            'contains_special_char' => false,
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
        'payment-api' => [
            'ban-time' => '946681200',
        ],
        'guard' => [
            Guard\UserBlock::class,
            Guard\UserRefresh::class,
        ],
        'donate' => [
            'sofort' => [
                /**
                 *  example packages
                'package_1' => [
                    'price' => 12.4, // must be a float
                    'amount' => 100, // must be a integer
                ],
                'package_2' => [
                    'price' => 12., // that is a float with a "." at the end
                    'amount' => 100,
                ],
                 */
            ],
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
    'navigation' => [
        'default' => [
            'home' => [
                'label' => 'Home',
                'route' => 'PServerCore',
                'resource' => 'PServerCore',
                'order' => -9999,
            ],
            'download' => [
                'label' => 'Download',
                'route' => 'PServerCore/site-download',
                'resource' => 'PServerCore/site-download',
                'order' => -100,
            ],
            'server-info' => [
                'label' => 'ServerInfo',
                'uri' => '#',
                'pages' => [
                    '1_position' => [
                        'label' => 'FAQ',
                        'route' => 'PServerCore/site-detail',
                        'params' => [
                            'type' => 'faq',
                        ],
                        'resource' => 'PServerCore/site-detail',
                    ],
                    '2_position' => [
                        'label' => 'Rules',
                        'route' => 'PServerCore/site-detail',
                        'params' => [
                            'type' => 'rules',
                        ],
                        'resource' => 'PServerCore/site-detail',
                    ],
                    '3_position' => [
                        'label' => 'Guides',
                        'route' => 'PServerCore/site-detail',
                        'params' => [
                            'type' => 'guides',
                        ],
                        'resource' => 'PServerCore/site-detail',
                    ],
                    '4_position' => [
                        'label' => 'Events',
                        'route' => 'PServerCore/site-detail',
                        'params' => [
                            'type' => 'events',
                        ],
                        'resource' => 'PServerCore/site-detail',
                    ],
                ],
                'order' => 0,
            ],
            'account' => [
                'id' => 'account',
                'label' => 'Account',
                'uri' => '#',
                'visible' => false,
                'pages' => [
                    'account_panel' => [
                        'label' => 'AccountPanel',
                        'route' => 'PServerCore/user',
                        'resource' => 'PServerCore/user',
                        'icon' => 'glyphicon glyphicon-user',
                        'order' => -30,
                    ],
                    'ticket_system' => [
                        'label' => 'TicketSystem',
                        'route' => 'zfc-ticketsystem',
                        'resource' => 'zfc-ticketsystem',
                        'icon' => 'fa fa-graduation-cap',
                        'order' => -20,
                        'pages' => [
                            'details' => [
                                'label' => 'Details',
                                'action' => 'view',
                                'route' => 'zfc-ticketsystem',
                                'visible' => false,
                            ],
                            'new' => [
                                'label' => 'New',
                                'action' => 'new',
                                'route' => 'zfc-ticketsystem',
                                'visible' => false,
                            ],
                        ],
                    ],
                    'donate' => [
                        'label' => 'Donate',
                        'route' => 'PServerCore/panel_donate',
                        'resource' => 'PServerCore/panel_donate',
                        'icon' => 'fa fa-usd',
                        'order' => -10,
                    ],
                    'admin_panel' => [
                        'label' => 'AdminPanel',
                        'route' => 'PServerAdmin/home',
                        'resource' => 'PServerAdmin/home',
                        'icon' => 'fa fa-graduation-cap',
                        'order' => 100,
                    ],
                ],
            ],
        ],
        'footer' => [
            'server-info' => [
                'label' => 'ServerInfo',
                'uri' => '#',
                'pages' => [
                    '1_position' => [
                        'label' => 'FAQ',
                        'route' => 'PServerCore/site-detail',
                        'params' => [
                            'type' => 'faq',
                        ],
                        'resource' => 'PServerCore/site-detail',
                    ],
                    '2_position' => [
                        'label' => 'Rules',
                        'route' => 'PServerCore/site-detail',
                        'params' => [
                            'type' => 'rules',
                        ],
                        'resource' => 'PServerCore/site-detail',
                    ],
                    '3_position' => [
                        'label' => 'Guides',
                        'route' => 'PServerCore/site-detail',
                        'params' => [
                            'type' => 'guides',
                        ],
                        'resource' => 'PServerCore/site-detail',
                    ],
                    '4_position' => [
                        'label' => 'Events',
                        'route' => 'PServerCore/site-detail',
                        'params' => [
                            'type' => 'events',
                        ],
                        'resource' => 'PServerCore/site-detail',
                    ],
                ],
            ],
            'policies' => [
                'label' => 'Policy',
                'uri' => '#',
                'pages' => [
                    'terms' => [
                        'label' => 'Terms & conditions',
                        'uri' => '#'
                    ],
                    'policy' => [
                        'label' => 'Privacy policy',
                        'uri' => '#'
                    ],
                ],
            ],
            'social' => [
                'label' => 'Social',
                'uri' => '#',
                'pages' => [
                    'facebook' => [
                        'label' => 'Facebook',
                        'target' => '_blank',
                        'uri' => '#'
                    ],
                    'youtube' => [
                        'label' => 'Youtube',
                        'target' => '_blank',
                        'uri' => 'https://www.youtube.com/'
                    ],
                    'github' => [
                        'label' => 'KoKsPfLaNzE',
                        'target' => '_blank',
                        'uri' => 'https://github.com/kokspflanze',
                    ],
                    'forum' => [
                        'label' => 'Forum',
                        'target' => '_blank',
                        'uri' => '#'
                    ],
                ],
            ],
        ],
    ],
];
