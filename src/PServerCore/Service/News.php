<?php

namespace PServerCore\Service;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use PServerAdmin\Mapper\HydratorNews;
use PServerCore\Entity\UserInterface;
use Zend\Paginator\Paginator;

class News extends InvokableBase
{
    /**
     * @return \PServerCore\Entity\News[]|Paginator
     */
    public function getActiveNews($page = 1)
    {
        $queryBuilder = $this->getNewsQueryBuilder();
        $queryBuilder->where('p.active = :active')
            ->setParameter('active', 1);

        $adapter = new DoctrineAdapter(new ORMPaginator($queryBuilder));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage($this->getConfigService()->get('pserver.news.limit', 5));
        $paginator->setCurrentPageNumber($page);

        return $paginator;
    }

    /**
     * @return null|\PServerCore\Entity\News[]
     */
    public function getNews()
    {
        /** @var \PServerCore\Entity\Repository\News $repository */
        $repository = $this->getEntityManager()->getRepository($this->getEntityOptions()->getNews());
        return $repository->getNews();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getNewsQueryBuilder()
    {
        /** @var \PServerCore\Entity\Repository\News $repository */
        $repository = $this->getEntityManager()->getRepository($this->getEntityOptions()->getNews());
        return $repository->getQueryBuilder();
    }

    /**
     * @param $newsId
     *
     * @return null|\PServerCore\Entity\News
     */
    public function getNews4Id($newsId)
    {
        /** @var \PServerCore\Entity\Repository\News $repository */
        $repository = $this->getEntityManager()->getRepository($this->getEntityOptions()->getNews());
        return $repository->getNews4Id($newsId);
    }

    /**
     * @param array $data
     * @param UserInterface $user
     * @param null $currentNews
     * @return bool|\PServerCore\Entity\News
     */
    public function news(array $data, UserInterface $user, $currentNews = null)
    {
        if (!$currentNews) {
            $currentNews = new \PServerCore\Entity\News();
        }

        $form = $this->getAdminNewsForm();
        $form->setHydrator(new HydratorNews());
        $form->bind($currentNews);
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }

        /** @var \PServerCore\Entity\News $news */
        $news = $form->getData();
        $news->setUser($this->getUser4Id($user->getId()));

        //\Zend\Debug\Debug::dump($user);die();

        $entity = $this->getEntityManager();
        $entity->persist($news);
        //$entity->persist($user);
        $entity->flush();

        return $news;
    }
} 