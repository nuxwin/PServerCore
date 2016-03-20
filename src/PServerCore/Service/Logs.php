<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use PServerCore\Options\EntityOptions;

class Logs
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  EntityOptions */
    protected $entityOptions;

    /**
     * Logs constructor.
     * @param EntityManager $entityManager
     * @param EntityOptions $entityOptions
     */
    public function __construct(EntityManager $entityManager, EntityOptions $entityOptions)
    {
        $this->entityManager = $entityManager;
        $this->entityOptions = $entityOptions;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getLogDataSource()
    {
        /** @var \PServerCore\Entity\Repository\Logs $repository */
        $repository = $this->entityManager->getRepository($this->entityOptions->getLogs());
        return $repository->getLogQueryBuilder();
    }
}