<?php

namespace PServerCore\Controller;

use PServerCore\Form\ChangePwd;
use PServerCore\Service\AddEmail;
use PServerCore\Service\User;
use Zend\Form;
use Zend\Mvc\Controller\AbstractActionController;

class AccountController extends AbstractActionController
{
    const ERROR_NAME_SPACE = 'pserver-user-account-error';
    const SUCCESS_NAME_SPACE = 'pserver-user-account-success';

    /** @var  User */
    protected $userService;

    /** @var  ChangePwd */
    protected $changePasswordForm;

    /** @var  AddEmail */
    protected $addEmailService;

    /**
     * AccountController constructor.
     * @param User $userService
     * @param ChangePwd $changePasswordForm
     * @param AddEmail $addEmailService
     */
    public function __construct(User $userService, ChangePwd $changePasswordForm, AddEmail $addEmailService)
    {
        $this->userService = $userService;
        $this->changePasswordForm = $changePasswordForm;
        $this->addEmailService = $addEmailService;
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function indexAction()
    {
        /** @var \PServerCore\Entity\UserInterface $user */
        $user = $this->userService->getAuthService()->getIdentity();

        $form = $this->changePasswordForm;
        $elements = $form->getElements();
        foreach ($elements as $element) {
            if ($element instanceof Form\Element) {
                $element->setValue('');
            }
        }

        $formChangeWebPwd = null;
        if (!$this->userService->isSamePasswordOption()) {
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
        if ($this->userService->$method($this->params()->fromPost(), $user)) {
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
            $this->userService->getAuthService()->getIdentity()
        );

        return $this->redirect()->toRoute('PServerCore/user', ['action' => 'index']);
    }

}