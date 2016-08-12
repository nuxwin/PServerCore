<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use PServerCore\Options\Collection;
use Zend\Paginator\Paginator;

class News
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Collection */
    protected $collectionOptions;

    /**
     * News constructor.
     * @param EntityManager $entityManager
     * @param Collection $collectionOptions
     */
    public function __construct(
        EntityManager $entityManager,
        Collection $collectionOptions
    ) {
        $this->entityManager = $entityManager;
        $this->collectionOptions = $collectionOptions;
    }

    /**
     * @param int $page
     * @return \PServerCore\Entity\News[]|Paginator
     */
    public function getActiveNews($page = 1)
    {
        $queryBuilder = $this->getNewsQueryBuilder();
        $queryBuilder->where('p.active = :active')
            ->setParameter('active', 1);

        $adapter = new DoctrineAdapter(new ORMPaginator($queryBuilder));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage($this->collectionOptions->getConfig()['news']['limit']);
        $paginator->setCurrentPageNumber($page);

        return $paginator;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getNewsQueryBuilder()
    {
        /** @var \PServerCore\Entity\Repository\News $repository */
        $repository = $this->entityManager->getRepository($this->collectionOptions->getEntityOptions()->getNews());
        return $repository->getQueryBuilder();
    }

} 