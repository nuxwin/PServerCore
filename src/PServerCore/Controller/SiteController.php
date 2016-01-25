<?php

namespace PServerCore\Controller;

use PServerCore\Helper\HelperService;
use PServerCore\Helper\HelperServiceLocator;
use Zend\Mvc\Controller\AbstractActionController;

class SiteController extends AbstractActionController
{
    use HelperServiceLocator, HelperService;

    /**
     * DownloadPage
     */
    public function downloadAction()
    {
        return [
            'downloadList' => $this->getDownloadService()->getActiveList()
        ];
    }

    /**
     * DynamicPages
     */
    public function pageAction()
    {
        $type = $this->params()->fromRoute('type');
        $pageInfo = $this->getPageInfoService()->getPage4Type($type);
        if (!$pageInfo) {
            return $this->redirect()->toRoute('PServerCore');
        }

        return [
            'pageInfo' => $pageInfo
        ];
    }

} 