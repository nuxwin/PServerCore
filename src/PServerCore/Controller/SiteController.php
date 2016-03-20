<?php

namespace PServerCore\Controller;

use PServerCore\Service\Download;
use PServerCore\Service\PageInfo;
use Zend\Mvc\Controller\AbstractActionController;

class SiteController extends AbstractActionController
{
    /** @var  Download */
    protected $downloadService;

    /** @var  PageInfo */
    protected $pageInfoService;

    /**
     * SiteController constructor.
     * @param Download $downloadService
     * @param PageInfo $pageInfoService
     */
    public function __construct(Download $downloadService, PageInfo $pageInfoService)
    {
        $this->downloadService = $downloadService;
        $this->pageInfoService = $pageInfoService;
    }

    /**
     * DownloadPage
     */
    public function downloadAction()
    {
        return [
            'downloadList' => $this->downloadService->getActiveList()
        ];
    }

    /**
     * DynamicPages
     */
    public function pageAction()
    {
        $type = $this->params()->fromRoute('type');
        $pageInfo = $this->pageInfoService->getPage4Type($type);
        if (!$pageInfo) {
            return $this->redirect()->toRoute('PServerCore');
        }

        return [
            'pageInfo' => $pageInfo
        ];
    }

} 