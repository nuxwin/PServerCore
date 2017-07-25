<?php

namespace PServerCore\Controller;

use PaymentAPI\Provider\Sofortueberweisung;
use PServerCore\Service\User;
use Zend\Mvc\Controller\AbstractActionController;

class DonateController extends AbstractActionController
{
    /** @var  User */
    protected $userService;

    /** @var  Sofortueberweisung */
    protected $sofortProvider;

    /** @var  array */
    protected $sofortConfig;

    /**
     * DonateController constructor.
     * @param User $userService
     * @param Sofortueberweisung $sofortProvider
     * @param array $sofortConfig
     */
    public function __construct(User $userService, Sofortueberweisung $sofortProvider, array $sofortConfig)
    {
        $this->userService = $userService;
        $this->sofortProvider = $sofortProvider;
        $this->sofortConfig = $sofortConfig;
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        /** @var \PServerCore\Entity\UserInterface $user */
        $user = $this->userService->getAuthService()->getIdentity();

        return [
            'user' => $user
        ];
    }

    public function sofortAction()
    {
        $option = $this->params()->fromPost('sofort', null);

        if (null === $option || !isset($this->sofortConfig[$option])) {
            $this->redirect()->toRoute('PServerCore/panel_donate');
        }

        $config = $this->sofortConfig[$option];

        /** @var \PServerCore\Entity\UserInterface $user */
        $user = $this->userService->getAuthService()->getIdentity();

        $this->redirect()->toUrl(
            $this->sofortProvider->getPaymentUrl(
                $user->getId(),
                $config['amount'],
                $config['price']
            )
        );
    }
}