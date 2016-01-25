<?php

namespace PServerCore\Controller;

use PServerCore\Helper\HelperService;
use PServerCore\Helper\HelperServiceLocator;
use Zend\Mvc\Controller\AbstractActionController;

class DonateController extends AbstractActionController
{
    use HelperServiceLocator, HelperService;

    public function indexAction()
    {
        /** @var \PServerCore\Entity\UserInterface $user */
        $user = $this->getUserService()->getAuthService()->getIdentity();

        return [
            'user' => $user
        ];
    }

}