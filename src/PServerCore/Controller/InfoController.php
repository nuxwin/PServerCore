<?php

namespace PServerCore\Controller;

use PServerCore\Service\PlayerHistory;
use Zend\Mvc\Controller\AbstractActionController;

class InfoController extends AbstractActionController
{
    /** @var  PlayerHistory */
    protected $playerHistory;

    /**
     * InfoController constructor.
     * @param PlayerHistory $playerHistory
     */
    public function __construct(PlayerHistory $playerHistory)
    {
        $this->playerHistory = $playerHistory;
    }

    /**
     * @return \Zend\Http\PhpEnvironment\Response
     */
    public function onlinePlayerAction()
    {
        /** @var \Zend\Http\PhpEnvironment\Response $response */
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', "image/png");

        $this->playerHistory->outputCurrentPlayerImage();

        $response->setStatusCode(200);

        return $response;
    }
}