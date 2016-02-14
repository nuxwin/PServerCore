<?php


namespace PServerCore\Helper;


trait HelperService
{

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getService('Doctrine\ORM\EntityManager');
    }

    /**
     * @return array|object
     */
    public function getConfig()
    {
        return $this->getService('Config');
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthService()
    {
        return $this->getService('small_user_auth_service');
    }

    /**
     * @return \Zend\Mvc\Controller\PluginManager
     */
    protected function getControllerPluginManager()
    {
        return $this->getService('ControllerPluginManager');
    }

    /**
     * @return \Zend\Cache\Storage\StorageInterface
     */
    protected function getCachingService()
    {
        return $this->getService('pserver_caching_service');
    }

    /**
     * @return \PServerCore\Service\CachingHelper
     */
    protected function getCachingHelperService()
    {
        return $this->getService('pserver_cachinghelper_service');
    }

    /**
     * @return \PServerCore\Service\ConfigRead
     */
    protected function getConfigService()
    {
        return $this->getService('pserver_configread_service');
    }

    /**
     * @return \PServerCore\Service\UserBlock
     */
    protected function getUserBlockService()
    {
        return $this->getService('pserver_user_block_service');
    }

    /**
     * @return \PServerCore\Service\User
     */
    protected function getUserService()
    {
        return $this->getService('small_user_service');
    }

    /**
     * @return \PServerCore\Service\UserCodes
     */
    protected function getUserCodesService()
    {
        return $this->getService('pserver_usercodes_service');
    }

    /**
     * @return \PServerCore\Service\Mail
     */
    protected function getMailService()
    {
        return $this->getService('pserver_mail_service');
    }

    /**
     * @return \PServerCore\Service\SecretQuestion
     */
    protected function getSecretQuestionService()
    {
        return $this->getService('pserver_secret_question');
    }

    /**
     * @return \PServerCore\Service\Coin
     */
    protected function getCoinService()
    {
        return $this->getService('pserver_coin_service');
    }

    /**
     * @return \PServerCore\Service\Download
     */
    protected function getDownloadService()
    {
        return $this->getService('pserver_download_service');
    }

    /**
     * @return \PServerCore\Service\PageInfo
     */
    protected function getPageInfoService()
    {
        return $this->getService('pserver_pageinfo_service');
    }

    /**
     * @return \PServerCore\Service\News
     */
    protected function getNewsService()
    {
        return $this->getService('pserver_news_service');
    }

    /**
     * @return \PServerCore\Service\UserRole
     */
    protected function getUserRoleService()
    {
        return $this->getService('pserver_user_role_service');
    }

    /**
     * @return \PServerCore\Service\ServerInfo
     */
    protected function getServerInfoService()
    {
        return $this->getService('pserver_server_info_service');
    }

    /**
     * @return \PServerCore\Service\Logs
     */
    protected function getWebLogService()
    {
        return $this->getService('pserver_log_service');
    }

    /**
     * @return \PServerCore\Service\Donate
     */
    protected function getDonateService()
    {
        return $this->getService('pserver_donate_service');
    }

    /**
     * @return \PServerCore\Service\LoginHistory
     */
    protected function getLoginHistoryService()
    {
        return $this->getService('pserver_login_history_service');
    }

    /**
     * @return \PServerCore\Service\Donate
     */
    protected function getDonateLogService()
    {
        return $this->getService('pserver_donate_service');
    }

    /**
     * @return \PServerCore\Service\PlayerHistory
     */
    protected function getPlayerHistory()
    {
        return $this->getService('pserver_playerhistory_service');
    }

    /**
     * @return \PServerCore\Service\Timer
     */
    protected function getTimerService()
    {
        return $this->getService('pserver_timer_service');
    }

    /**
     * @return \PServerCore\Service\Ip
     */
    protected function getIpService()
    {
        return $this->getService('payment_api_ip_service');
    }

    /**
     * @return \PServerCore\Service\AddEmail
     */
    protected function getAddEmailService()
    {
        return $this->getService('pserver_add_email_service');
    }

    /**
     * @return \PServerCore\Service\Format
     */
    protected function getFormatService()
    {
        return $this->getService('pserver_format_service');
    }

    /**
     * @param $serviceName
     *
     * @return array|object
     */
    public abstract function getService($serviceName);

}