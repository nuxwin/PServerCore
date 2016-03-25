<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use PServerCore\Entity\News as NewsEntity;
use PServerCore\Entity\UserInterface;
use PServerCore\Mapper\HydratorNews;
use PServerCore\Options\Collection;
use Zend\Form\FormInterface;
use Zend\Paginator\Paginator;

class News
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Collection */
    protected $collectionOptions;

    /** @var  FormInterface */
    protected $adminNewsForm;

    /**
     * News constructor.
     * @param EntityManager $entityManager
     * @param Collection $collectionOptions
     * @param FormInterface $adminNewsForm
     */
    public function __construct(
        EntityManager $entityManager,
        Collection $collectionOptions,
        FormInterface $adminNewsForm
    ) {
        $this->entityManager = $entityManager;
        $this->collectionOptions = $collectionOptions;
        $this->adminNewsForm = $adminNewsForm;
    }

    /**
     * @return NewsEntity[]|Paginator
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
     * @return null|NewsEntity[]
     */
    public function getNews()
    {
        /** @var \PServerCore\Entity\Repository\News $repository */
        $repository = $this->entityManager->getRepository($this->collectionOptions->getEntityOptions()->getNews());
        return $repository->getNews();
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

    /**
     * @param $newsId
     *
     * @return null|NewsEntity
     */
    public function getNews4Id($newsId)
    {
        /** @var \PServerCore\Entity\Repository\News $repository */
        $repository = $this->entityManager->getRepository($this->collectionOptions->getEntityOptions()->getNews());
        return $repository->getNews4Id($newsId);
    }

    /**
     * @param array $data
     * @param UserInterface $user
     * @param null $currentNews
     * @return bool|NewsEntity
     */
    public function news(array $data, UserInterface $user, $currentNews = null)
    {
        if (!$currentNews) {
            $currentNews = new NewsEntity();
        }

        $form = $this->adminNewsForm;
        $form->setHydrator(new HydratorNews());
        $form->bind($currentNews);
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        /** @var NewsEntity $news */
        $news = $form->getData();
        $news->setUser($this->entityManager->merge($user));

        $this->entityManager->persist($news);
        //$entity->persist($user);
        $this->entityManager->flush();

        return $news;
    }

    /**
     * @return FormInterface
     */
    public function getAdminNewsForm()
    {
        return $this->adminNewsForm;
    }


} 