<?php


namespace PServerCore\View\Helper;

use PServerCore\Service\News;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class NewsWidget extends AbstractHelper
{
    /** @var  News */
    protected $newsService;

    /**
     * NewsWidget constructor.
     * @param News $newsService
     */
    public function __construct(News $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * @return string
     */
    public function __invoke()
    {
        $viewModel = new ViewModel([
            'newsList' => $this->newsService->getActiveNews()
        ]);
        $viewModel->setTemplate('helper/newsWidget');

        return $this->getView()->render($viewModel);
    }
}