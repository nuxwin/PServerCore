<?php

namespace PServerCore\Controller;

use PServerCore\Service\News;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    /** @var  News */
    protected $newsService;

    /**
     * IndexController constructor.
     * @param News $newsService
     */
    public function __construct(News $newsService)
    {
        $this->newsService = $newsService;
    }

    public function indexAction()
    {
        $pageNumber = (int)$this->params()->fromRoute('page');

        $newsList = $this->newsService->getActiveNews($pageNumber);

        return [
            'newsList' => $newsList
        ];
    }

}
