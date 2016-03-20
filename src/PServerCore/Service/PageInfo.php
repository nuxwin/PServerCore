<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use PServerCore\Keys\Caching;
use PServerCore\Mapper\HydratorPageInfo;
use PServerCore\Options\Collection;
use Zend\Form\Form;

class PageInfo
{
    /** @var  CachingHelper */
    protected $cachingHelperService;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Collection */
    protected $collectionOptions;

    /** @var  Form */
    protected $adminPageInfoForm;

    /**
     * PageInfo constructor.
     * @param CachingHelper $cachingHelperService
     * @param EntityManager $entityManager
     * @param Collection $collectionOptions
     * @param Form $adminPageInfoForm
     */
    public function __construct(
        CachingHelper $cachingHelperService,
        EntityManager $entityManager,
        Collection $collectionOptions,
        Form $adminPageInfoForm
    ) {
        $this->cachingHelperService = $cachingHelperService;
        $this->entityManager = $entityManager;
        $this->collectionOptions = $collectionOptions;
        $this->adminPageInfoForm = $adminPageInfoForm;
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

    /**
     * @param array $data
     * @param string $type
     *
     * @return bool|\PServerCore\Entity\PageInfo
     */
    public function pageInfo(array $data, $type)
    {
        $form = $this->adminPageInfoForm;
        $form->setHydrator(new HydratorPageInfo());
        $class = $this->collectionOptions->getEntityOptions()->getPageInfo();
        $form->bind(new $class());
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }

        /** @var \PServerCore\Entity\PageInfo $pageInfo */
        $pageInfo = $form->getData();
        $pageInfo->setType($type);

        $this->entityManager->persist($pageInfo);
        $this->entityManager->flush();

        return $pageInfo;
    }

    /**
     * @return string[]
     */
    public function getPossiblePageInfoTypes()
    {
        return $this->collectionOptions->getConfig()['pageinfotype'];
    }

} 