<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use PServerCore\Keys\Caching;
use PServerCore\Options\Collection;

class PageInfo
{
    /** @var  CachingHelper */
    protected $cachingHelperService;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Collection */
    protected $collectionOptions;

    /**
     * PageInfo constructor.
     * @param CachingHelper $cachingHelperService
     * @param EntityManager $entityManager
     * @param Collection $collectionOptions
     */
    public function __construct(
        CachingHelper $cachingHelperService,
        EntityManager $entityManager,
        Collection $collectionOptions
    ) {
        $this->cachingHelperService = $cachingHelperService;
        $this->entityManager = $entityManager;
        $this->collectionOptions = $collectionOptions;
    }

    /**
     * @param $type
     *
     * @return \PServerCore\Entity\PageInfo|null
     */
    public function getPage4Type($type)
    {
        $cachingKey = Caching::PAGE_INFO . '_' . $type;

        $pageInfo = $this->cachingHelperService->getItem($cachingKey, function () use ($type) {
            /** @var \PServerCore\Entity\Repository\PageInfo $repository */
            $repository = $this->entityManager->getRepository(
                $this->collectionOptions->getEntityOptions()->getPageInfo()
            );
            return $repository->getPageData4Type($type);
        });

        return $pageInfo;
    }

} 