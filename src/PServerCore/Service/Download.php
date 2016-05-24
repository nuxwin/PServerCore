<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use PServerCore\Entity\DownloadList;
use PServerCore\Keys\Caching;
use PServerCore\Mapper\HydratorDownload;
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
     * @param FormInterface $adminDownloadForm
     */
    public function __construct(
        EntityManager $entityManager,
        EntityOptions $entityOptions,
        CachingHelper $cachingHelperService,
        FormInterface $adminDownloadForm
    ) {
        $this->entityManager = $entityManager;
        $this->entityOptions = $entityOptions;
        $this->cachingHelperService = $cachingHelperService;
        $this->adminDownloadForm = $adminDownloadForm;
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
     * @return null|\PServerCore\Entity\DownloadList[]
     */
    public function getDownloadList()
    {
        return $this->getDownloadRepository()->getDownloadList();
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
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->getDownloadRepository()->getQueryBuilder();
    }

    /**
     * @param array $data
     * @param null|DownloadList $currentDownload
     *
     * @return bool|DownloadList
     */
    public function download(array $data, $currentDownload = null)
    {
        if ($currentDownload == null) {
            $class = $this->entityOptions->getDownloadList();
            /** @var DownloadList $currentDownload */
            $currentDownload = new $class;
        }

        $form = $this->adminDownloadForm;
        $form->setData($data);
        $form->setHydrator(new HydratorDownload());
        $form->bind($currentDownload);
        if (!$form->isValid()) {
            return false;
        }

        /** @var DownloadList $download */
        $download = $form->getData();

        $this->entityManager->persist($download);
        $this->entityManager->flush();

        return $download;
    }

    /**
     * @param DownloadList $downloadEntry
     * @return mixed
     */
    public function deleteDownloadEntry(DownloadList $downloadEntry)
    {
        return $this->getDownloadRepository()->deleteDownloadEntry($downloadEntry->getId());
    }

    /**
     * @return FormInterface
     */
    public function getAdminDownloadForm()
    {
        return $this->adminDownloadForm;
    }

    /**
     * @return \PServerCore\Entity\Repository\DownloadList
     */
    protected function getDownloadRepository()
    {
        return $this->entityManager->getRepository($this->entityOptions->getDownloadList());
    }


} 