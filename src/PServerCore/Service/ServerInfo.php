<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use PServerCore\Keys\Caching;
use PServerCore\Mapper\HydratorServerInfo;
use PServerCore\Options\EntityOptions;
use Zend\Form\FormInterface;

class ServerInfo
{
    /** @var  CachingHelper */
    protected $cachingHelperService;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  EntityOptions */
    protected $entityOptions;

    /** @var  FormInterface */
    protected $adminServerInfoForm;

    /**
     * ServerInfo constructor.
     * @param CachingHelper $cachingHelperService
     * @param EntityManager $entityManager
     * @param EntityOptions $entityOptions
     * @param FormInterface $adminServerInfoForm
     */
    public function __construct(
        CachingHelper $cachingHelperService,
        EntityManager $entityManager,
        EntityOptions $entityOptions,
        FormInterface $adminServerInfoForm
    ) {
        $this->cachingHelperService = $cachingHelperService;
        $this->entityManager = $entityManager;
        $this->entityOptions = $entityOptions;
        $this->adminServerInfoForm = $adminServerInfoForm;
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
     * @return null|\PServerCore\Entity\ServerInfo[]
     */
    public function getAllServerInfo()
    {
        return $this->getServerInfoRepository()->getInfoList();
    }

    /**
     * @param $id
     *
     * @return null|\PServerCore\Entity\ServerInfo
     */
    public function getServerInfo4Id($id)
    {
        return $this->getServerInfoRepository()->getServerInfo4Id($id);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->getServerInfoRepository()->getQueryBuilder();
    }

    /**
     * @param array $data
     * @param null $currentServerInfo
     *
     * @return bool|\PServerCore\Entity\ServerInfo
     */
    public function serverInfo(array $data, $currentServerInfo = null)
    {
        if ($currentServerInfo == null) {
            $class = $this->entityOptions->getServerInfo();
            $currentServerInfo = new $class;
        }

        $form = $this->adminServerInfoForm;
        $form->setHydrator(new HydratorServerInfo());
        $form->bind($currentServerInfo);
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        /** @var \PServerCore\Entity\ServerInfo $serverInfo */
        $serverInfo = $form->getData();

        $entity = $this->entityManager;
        $entity->persist($serverInfo);
        $entity->flush();

        return $serverInfo;
    }

    /**
     * @return FormInterface
     */
    public function getAdminServerInfoForm()
    {
        return $this->adminServerInfoForm;
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