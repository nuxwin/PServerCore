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
		$pageNumber = (int)$this->params()->fromRoute('page');

		$newsList = $this->getNewsService()->getActiveNews($pageNumber);

		// @deprecated sNews remove in 1.0
		return [
			'aNews' => $newsList,
			'newsList' => $newsList
		];
	}

}
