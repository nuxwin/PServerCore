<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use PServerCore\Entity\DownloadList;
use PServerCore\Keys\Caching;
use PServerCore\Options\EntityOptions;
use Zend\Form\FormInterface;

class Download
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  EntityOptions */
    protected $entityOptions;

    /** @var  CachingHelper */
    protected $cachingHelperService;

    /** @var  FormInterface */
    protected $adminDownloadForm;

    /**
     * Download constructor.
     * @param EntityManager $entityManager
     * @param EntityOptions $entityOptions
     * @param CachingHelper $cachingHelperService
     */
    public function __construct(
        EntityManager $entityManager,
        EntityOptions $entityOptions,
        CachingHelper $cachingHelperService
    ) {
        $this->entityManager = $entityManager;
        $this->entityOptions = $entityOptions;
        $this->cachingHelperService = $cachingHelperService;
    }

    /**
     * @return \PServerCore\Entity\DownloadList[]
     */
    public function getActiveList()
    {
        $downloadInfo = $this->cachingHelperService->getItem(Caching::DOWNLOAD, function () {
            return $this->getDownloadRepository()->getActiveDownloadList();
        });

        return $downloadInfo;
    }

    /**
     * @param $id
     * @return null|DownloadList
     */
    public function getDownload4Id($id)
    {
        return $this->getDownloadRepository()->getDownload4Id($id);
    }

    /**
     * @return \PServerCore\Entity\Repository\DownloadList
     */
    protected function getDownloadRepository()
    {
        return $this->entityManager->getRepository($this->entityOptions->getDownloadList());
    }


} 