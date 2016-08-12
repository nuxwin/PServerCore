<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use PServerCore\Keys\Caching;
use PServerCore\Options\EntityOptions;

class ServerInfo
{
    /** @var  CachingHelper */
    protected $cachingHelperService;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  EntityOptions */
    protected $entityOptions;

    /**
     * ServerInfo constructor.
     * @param CachingHelper $cachingHelperService
     * @param EntityManager $entityManager
     * @param EntityOptions $entityOptions
     */
    public function __construct(
        CachingHelper $cachingHelperService,
        EntityManager $entityManager,
        EntityOptions $entityOptions
    ) {
        $this->cachingHelperService = $cachingHelperService;
        $this->entityManager = $entityManager;
        $this->entityOptions = $entityOptions;
    }

    /**
     * @return null|\PServerCore\Entity\ServerInfo[]
     */
    public function getServerInfo()
    {
        $serverInfo = $this->cachingHelperService->getItem(Caching::SERVER_INFO, function () {
            return $this->getServerInfoRepository()->getActiveInfoList();
        });

        return $serverInfo;
    }


    /**
     * @return \PServerCore\Entity\Repository\ServerInfo
     */
    protected function getServerInfoRepository()
    {
        /** @var \PServerCore\Entity\Repository\ServerInfo $repository */
        $repository = $this->entityManager->getRepository($this->entityOptions->getServerInfo());
        return $repository;
    }
} 