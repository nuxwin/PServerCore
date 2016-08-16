<?php

namespace PServerCore\Controller;

use PServerCore\Form\ChangePwd;
use PServerCore\Service;
use Zend\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationServiceInterface;

class AccountController extends AbstractActionController
{
    const ERROR_NAME_SPACE = 'pserver-user-account-error';
    const SUCCESS_NAME_SPACE = 'pserver-user-account-success';

    /** @var  Service\Account */
    protected $accountService;

    /** @var  ChangePwd */
    protected $changePasswordForm;

    /** @var  Service\AddEmail */
    protected $addEmailService;

    /** @var  AuthenticationServiceInterface */
    protected $authService;

    /**
     * AccountController constructor.
     * @param Service\Account                $accountService
     * @param ChangePwd                      $changePasswordForm
     * @param Service\AddEmail               $addEmailService
     * @param AuthenticationServiceInterface $authService
     */
    public function __construct(
        Service\Account $accountService,
        ChangePwd $changePasswordForm,
        Service\AddEmail $addEmailService,
        AuthenticationServiceInterface $authService
    ) {
        $this->accountService = $accountService;
        $this->changePasswordForm = $changePasswordForm;
        $this->addEmailService = $addEmailService;
        $this->authService = $authService;
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function indexAction()
    {
        /** @var \PServerCore\Entity\UserInterface $user */
        $user = $this->authService->getIdentity();

        $form = $this->changePasswordForm;
        $elements = $form->getElements();
        foreach ($elements as $element) {
            if ($element instanceof Form\Element) {
                $element->setValue('');
            }
        }

        $formChangeWebPwd = null;
        if (!$this->accountService->isSamePasswordOption()) {
            $webPasswordForm = clone $form;
            $formChangeWebPwd = $webPasswordForm->setWhich('web');
        }

        $inGamePasswordForm = clone $form;
        $formChangeIngamePwd = $inGamePasswordForm->setWhich('ingame');

        $addEmailForm = null;
        if (!$user->getEmail()) {
            $addEmailForm = $this->addEmailService->getAddEmailForm();
        }

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return [
                'changeWebPwdForm' => $formChangeWebPwd,
                'changeIngamePwdForm' => $formChangeIngamePwd,
                'addEmailForm' => $addEmailForm,
                'messagesWeb' => $this->flashMessenger()->getMessagesFromNamespace($this::SUCCESS_NAME_SPACE . 'Web'),
                'messagesInGame' => $this->flashMessenger()->getMessagesFromNamespace($this::SUCCESS_NAME_SPACE . 'InGame'),
                'messagesAddEmail' => $this->flashMessenger()->getMessagesFromNamespace($this::SUCCESS_NAME_SPACE . 'AddEmail'),
                'errorsWeb' => $this->flashMessenger()->getMessagesFromNamespace($this::ERROR_NAME_SPACE . 'Web'),
                'errorsInGame' => $this->flashMessenger()->getMessagesFromNamespace($this::ERROR_NAME_SPACE . 'InGame'),
                'errorsAddEmail' => $this->flashMessenger()->getMessagesFromNamespace($this::ERROR_NAME_SPACE . 'AddEmail'),
            ];

        }

        $method = $this->params()->fromPost('which') == 'ingame' ? 'changeInGamePwd' : 'changeWebPwd';
        if ($this->accountService->$method($this->params()->fromPost(), $user)) {
            $successKey = $this::SUCCESS_NAME_SPACE;
            if ($this->params()->fromPost('which') == 'ingame') {
                $successKey .= 'InGame';
            } else {
                $successKey .= 'Web';
            }
            $this->flashMessenger()->setNamespace($successKey)->addMessage('Success, password changed.');
        }

        return $this->redirect()->toUrl($this->url()->fromRoute('PServerCore/user'));
    }

    /**
     * @return \Zend\Http\Response
     */
    public function addEmailAction()
    {
        $this->addEmailService->addEmail(
            $this->params()->fromPost(),
            $this->authService->getIdentity()
        );

        return $this->redirect()->toRoute('PServerCore/user', ['action' => 'index']);
    }

}