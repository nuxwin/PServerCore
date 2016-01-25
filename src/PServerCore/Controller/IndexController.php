<?php

namespace PServerCore\Controller;

use PServerCore\Helper\HelperService;
use PServerCore\Helper\HelperServiceLocator;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    use HelperServiceLocator, HelperService;

	public function indexAction()
    {
		return [
			'aNews' => $this->getNewsService()->getActiveNews()
		];
	}

}
