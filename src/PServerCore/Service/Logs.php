<?php


namespace PServerCore\Service;


class Logs extends InvokableBase
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getLogDataSource()
    {
        /** @var \PServerCore\Entity\Repository\Logs $repository */
        $repository = $this->getEntityManager()->getRepository($this->getEntityOptions()->getLogs());
        return $repository->getLogQueryBuilder();
    }
}