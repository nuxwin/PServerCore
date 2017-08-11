<?php

namespace PServerCore\Options;

use PServerCore\Entity;
use Zend\Stdlib\AbstractOptions;

class EntityOptions extends AbstractOptions
{
    protected $__strictMode__ = false;

    /**
     * @var string
     */
    protected $availableCountries = Entity\AvailableCountries::class;

    /**
     * @var string
     */
    protected $countryList = Entity\CountryList::class;

    /**
     * @var string
     */
    protected $donateLog = Entity\DonateLog::class;

    /**
     * @var string
     */
    protected $downloadList = Entity\DownloadList::class;

    /**
     * @var string
     */
    protected $ipBlock = Entity\IpBlock::class;

    /**
     * @var string
     */
    protected $loginFailed = Entity\LoginFailed::class;

    /**
     * @var string
     */
    protected $loginHistory = Entity\LoginHistory::class;

    /**
     * @var string
     */
    protected $logs = Entity\Logs::class;

    /**
     * @var string
     */
    protected $news = Entity\News::class;

    /**
     * @var string
     */
    protected $pageInfo = Entity\PageInfo::class;

    /**
     * @var string
     */
    protected $playerHistory = Entity\PlayerHistory::class;

    /**
     * @var string
     */
    protected $secretAnswer = Entity\SecretAnswer::class;

    /**
     * @var string
     */
    protected $secretQuestion = Entity\SecretQuestion::class;

    /**
     * @var string
     */
    protected $serverInfo = Entity\ServerInfo::class;

    /**
     * @var string
     */
    protected $userBlock = Entity\UserBlock::class;

    /**
     * @var string
     */
    protected $userCodes = Entity\UserCodes::class;

    /**
     * @var string
     */
    protected $userExtension = Entity\UserExtension::class;

    /**
     * @var string
     */
    protected $userRole = Entity\UserRole::class;

    /**
     * @var string
     */
    protected $user = Entity\User::class;

    /**
     * @return string
     */
    public function getAvailableCountries()
    {
        return $this->availableCountries;
    }

    /**
     * @param string $availableCountries
     *
     * @return self
     */
    public function setAvailableCountries($availableCountries)
    {
        $this->availableCountries = $availableCountries;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryList()
    {
        return $this->countryList;
    }

    /**
     * @param string $countryList
     *
     * @return self
     */
    public function setCountryList($countryList)
    {
        $this->countryList = $countryList;
        return $this;
    }

    /**
     * @return string
     */
    public function getDonateLog()
    {
        return $this->donateLog;
    }

    /**
     * @param string $donateLog
     *
     * @return self
     */
    public function setDonateLog($donateLog)
    {
        $this->donateLog = $donateLog;
        return $this;
    }

    /**
     * @return string
     */
    public function getDownloadList()
    {
        return $this->downloadList;
    }

    /**
     * @param string $downloadList
     *
     * @return self
     */
    public function setDownloadList($downloadList)
    {
        $this->downloadList = $downloadList;
        return $this;
    }

    /**
     * @return string
     */
    public function getIpBlock()
    {
        return $this->ipBlock;
    }

    /**
     * @param string $ipBlock
     *
     * @return self
     */
    public function setIpBlock($ipBlock)
    {
        $this->ipBlock = $ipBlock;
        return $this;
    }

    /**
     * @return string
     */
    public function getLoginFailed()
    {
        return $this->loginFailed;
    }

    /**
     * @param string $loginFailed
     *
     * @return self
     */
    public function setLoginFailed($loginFailed)
    {
        $this->loginFailed = $loginFailed;
        return $this;
    }

    /**
     * @return string
     */
    public function getLoginHistory()
    {
        return $this->loginHistory;
    }

    /**
     * @param string $loginHistory
     *
     * @return self
     */
    public function setLoginHistory($loginHistory)
    {
        $this->loginHistory = $loginHistory;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param string $logs
     *
     * @return self
     */
    public function setLogs($logs)
    {
        $this->logs = $logs;
        return $this;
    }

    /**
     * @return string
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param string $news
     *
     * @return self
     */
    public function setNews($news)
    {
        $this->news = $news;
        return $this;
    }

    /**
     * @return string
     */
    public function getPageInfo()
    {
        return $this->pageInfo;
    }

    /**
     * @param string $pageInfo
     *
     * @return self
     */
    public function setPageInfo($pageInfo)
    {
        $this->pageInfo = $pageInfo;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlayerHistory()
    {
        return $this->playerHistory;
    }

    /**
     * @param string $playerHistory
     *
     * @return self
     */
    public function setPlayerHistory($playerHistory)
    {
        $this->playerHistory = $playerHistory;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecretAnswer()
    {
        return $this->secretAnswer;
    }

    /**
     * @param string $secretAnswer
     *
     * @return self
     */
    public function setSecretAnswer($secretAnswer)
    {
        $this->secretAnswer = $secretAnswer;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecretQuestion()
    {
        return $this->secretQuestion;
    }

    /**
     * @param string $secretQuestion
     *
     * @return self
     */
    public function setSecretQuestion($secretQuestion)
    {
        $this->secretQuestion = $secretQuestion;
        return $this;
    }

    /**
     * @return string
     */
    public function getServerInfo()
    {
        return $this->serverInfo;
    }

    /**
     * @param string $serverInfo
     *
     * @return self
     */
    public function setServerInfo($serverInfo)
    {
        $this->serverInfo = $serverInfo;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserBlock()
    {
        return $this->userBlock;
    }

    /**
     * @param string $userBlock
     *
     * @return self
     */
    public function setUserBlock($userBlock)
    {
        $this->userBlock = $userBlock;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserCodes()
    {
        return $this->userCodes;
    }

    /**
     * @param string $userCodes
     *
     * @return self
     */
    public function setUserCodes($userCodes)
    {
        $this->userCodes = $userCodes;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserExtension()
    {
        return $this->userExtension;
    }

    /**
     * @param string $userExtension
     *
     * @return self
     */
    public function setUserExtension($userExtension)
    {
        $this->userExtension = $userExtension;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserRole()
    {
        return $this->userRole;
    }

    /**
     * @param string $userRole
     *
     * @return self
     */
    public function setUserRole($userRole)
    {
        $this->userRole = $userRole;
        return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     *
     * @return self
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

}