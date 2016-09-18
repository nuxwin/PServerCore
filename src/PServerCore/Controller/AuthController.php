<?php

namespace PServerCore\Controller;

use PServerCore\Entity\UserCodes;
use PServerCore\Form\Password;
use PServerCore\Service\AddEmail;
use PServerCore\Service\User;
use PServerCore\Service\UserCodes as UserCodesService;
use SmallUser\Controller\AuthController as SmallUserAuthController;

class AuthController extends SmallUserAuthController
{
    /** @var  User */
    protected $userService;

    /** @var  UserCodesService */
    protected $userCodes;

    /** @var  AddEmail */
    protected $addEmailService;

    /**
     * AuthController constructor.
     * @param User $userService
     * @param UserCodesService $userCodes
     * @param AddEmail $addEmailService
     */
    public function __construct(User $userService, UserCodesService $userCodes, AddEmail $addEmailService)
    {
        parent::__construct($userService);

        $this->userCodes = $userCodes;
        $this->addEmailService = $addEmailService;
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function registerAction()
    {

        //if already login, redirect to success page
        if ($this->userService->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getLoggedInRoute());
        }

        $form = $this->userService->getRegisterForm();

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $user = $this->getUserService()->register($this->params()->fromPost());
            if ($user) {
                return $this->redirect()->toRoute('small-user-auth', ['action' => 'register-done']);
            }
        }

        return [
            'registerForm' => $form
        ];
    }

    /**
     * @return array
     */
    public function registerDoneAction()
    {
        return [
            'mail_confirmation' => $this->userService->isRegisterMailConfirmationOption()
        ];
    }

    /**
     * @return array|mixed|\Zend\Http\Response
     */
    public function registerConfirmAction()
    {
        $codeRoute = $this->params()->fromRoute('code');

        $userCode = $this->userCodes->getCode4Data($codeRoute, UserCodes::TYPE_REGISTER);
        if (!$userCode) {
            return $this->forward()->dispatch('PServerCore\Controller\Auth', ['action' => 'wrong-code']);
        }

        $form = null;
        $passwordSame = $this->userService->isSamePasswordOption();

        if (!$passwordSame) {
            $form = $this->userService->getPasswordForm();
        }

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            if (!$passwordSame) {
                $user = $this->userService->registerGameWithOtherPw($this->params()->fromPost(), $userCode);
            } else {
                $user = $this->userService->registerGameForm($userCode);
            }
            if ($user) {
                //$this->getUserService()->doAuthentication($user);
                return $this->redirect()->toRoute($this->getLoggedInRoute());
            }
        }

        return [
            'registerForm' => $form,
            'passwordSame' => $passwordSame
        ];
    }

    /**
     * @return array|mixed|\Zend\Http\Response
     */
    public function ipConfirmAction()
    {
        $code = $this->params()->fromRoute('code');

        $oCode = $this->userCodes->getCode4Data($code, UserCodes::TYPE_CONFIRM_COUNTRY);
        if (!$oCode) {
            return $this->forward()->dispatch('PServerCore\Controller\Auth', ['action' => 'wrong-code']);
        }

        $user = $this->userService->countryConfirm($oCode);
        if ($user) {
            return $this->redirect()->toRoute('small-user-auth', ['action' => 'ip-confirm-done']);
        }

        return [];
    }

    /**
     * @return array
     */
    public function ipConfirmDoneAction()
    {
        return [];
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function pwLostAction()
    {

        $form = $this->userService->getPasswordLostForm();

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $user = $this->userService->lostPw($this->params()->fromPost());
            if ($user) {
                return $this->redirect()->toRoute('small-user-auth', ['action' => 'pw-lost-done']);
            }
        }

        return [
            'pwLostForm' => $form
        ];
    }

    /**
     * @return array
     */
    public function pwLostDoneAction()
    {
        return [];
    }

    /**
     * @return array|mixed|\Zend\Http\Response
     */
    public function pwLostConfirmAction()
    {
        $code = $this->params()->fromRoute('code');

        $codeEntity = $this->userCodes->getCode4Data($code, UserCodes::TYPE_LOST_PASSWORD);
        if (!$codeEntity) {
            return $this->forward()->dispatch('PServerCore\Controller\Auth', ['action' => 'wrong-code']);
        }

        /** @var Password $form */
        $form = $this->userService->getPasswordForm();
        $form->addSecretQuestion($codeEntity->getUser());
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $user = $this->userService->lostPwConfirm($this->params()->fromPost(), $codeEntity);
            if ($user) {
                return $this->redirect()->toRoute('small-user-auth', ['action' => 'pw-lost-confirm-done']);
            }
        }

        return [
            'pwLostForm' => $form
        ];
    }

    /**
     * @return mixed|\Zend\Http\Response
     */
    public function secretLoginAction()
    {
        $code = $this->params()->fromRoute('code');

        $codeEntity = $this->userCodes->getCode4Data($code, UserCodes::TYPE_SECRET_LOGIN);
        if (!$codeEntity) {
            return $this->forward()->dispatch('PServerCore\Controller\Auth', ['action' => 'wrong-code']);
        }
        $this->userService->doAuthentication($codeEntity->getUser());
        $this->userCodes->deleteCode($codeEntity);

        return $this->redirect()->toRoute($this->getLoggedInRoute());
    }

    /**
     * @return array
     */
    public function pwLostConfirmDoneAction()
    {
        return [];
    }

    /**
     * @return array
     */
    public function wrongCodeAction()
    {
        return [];
    }

    /**
     * @return mixed
     */
    public function addEmailAction()
    {
        $code = $this->params()->fromRoute('code');

        $codeEntity = $this->userCodes->getCode4Data($code, UserCodes::TYPE_ADD_EMAIL);
        if (!$codeEntity) {
            return $this->forward()->dispatch('PServerCore\Controller\Auth', ['action' => 'wrong-code']);
        }
        $user = $this->addEmailService->changeMail($codeEntity->getUser());
        $this->userCodes->deleteCode($codeEntity);

        $this->userService->doAuthentication($user);

        return null;
    }

}
