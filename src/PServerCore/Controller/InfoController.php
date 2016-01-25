<?php


namespace PServerCore\Controller;

use PServerCore\Helper\HelperService;
use PServerCore\Helper\HelperServiceLocator;
use Zend\Mvc\Controller\AbstractActionController;

class InfoController extends AbstractActionController
{
    use HelperServiceLocator, HelperService;

    public function onlinePlayerAction()
    {
        /** @var \Zend\Http\PhpEnvironment\Response $response */
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', "image/png");

        $this->getPlayerHistory()->outputCurrentPlayerImage();

        $response->setStatusCode(200);

        return $response;
    }
}