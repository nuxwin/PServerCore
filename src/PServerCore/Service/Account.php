<?php


namespace PServerCore\Service;

use GameBackend\DataService\DataServiceInterface;
use PServerCore\Controller\AccountController;
use PServerCore\Entity\UserInterface;
use PServerCore\Options\PasswordOptions;
use Zend\Form\FormInterface;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;

class Account
{
    /** @var  PasswordOptions */
    protected $passwordOption;

    /** @var  FormInterface */
    protected $changePasswordForm;

    /** @var  User */
    protected $userService;

    /** @var  DataServiceInterface */
    protected $gameBackendService;

    /** @var  FlashMessenger */
    protected $flashMessenger;

    /**
     * Account constructor.
     * @param PasswordOptions $passwordOption
     * @param FormInterface $changePasswordForm
     * @param User $userService
     * @param DataServiceInterface $gameBackendService
     * @param FlashMessenger $flashMessenger
     */
    public function __construct(
        PasswordOptions $passwordOption,
        FormInterface $changePasswordForm,
        User $userService,
        DataServiceInterface $gameBackendService,
        FlashMessenger $flashMessenger
    ) {
        $this->passwordOption = $passwordOption;
        $this->changePasswordForm = $changePasswordForm;
        $this->userService = $userService;
        $this->gameBackendService = $gameBackendService;
        $this->flashMessenger = $flashMessenger;
    }

    /**
     * @param array $data
     * @param UserInterface $user
     * @return bool|null|UserInterface
     */
    public function changeWebPwd(array $data, UserInterface $user)
    {
        $user = $this->userService->getUser4Id($user->getId());

        // check if we use different pw system
        if ($this->isSamePasswordOption()) {
            return false;
        }

        if (!$this->isPwdChangeAllowed($data, $user, 'Web')) {
            return false;
        }

        $user = $this->userService->setNewPasswordAtUser($user, $data['password']);

        return $user;
    }

    /**
     * @param array $data
     * @param UserInterface $user
     * @return bool
     */
    public function changeInGamePwd(array $data, UserInterface $user)
    {
        $user = $this->userService->getUser4Id($user->getId());
        if (!$this->isPwdChangeAllowed($data, $user, 'InGame')) {
            return false;
        }

        // check if we have to change it at web too
        if ($this->isSamePasswordOption()) {
            $user = $this->userService->setNewPasswordAtUser($user, $data['password']);
        }

        $this->gameBackendService->setUser($user, $data['password']);

        return $user;
    }

    /**
     * @return bool
     */
    public function isSamePasswordOption()
    {
        return !$this->passwordOption->isDifferentPasswords();
    }

    /**
     * @TODO better error handling
     *
     * @param array $data
     * @param UserInterface $user
     * @param string $errorExtension
     *
     * @return bool
     */
    protected function isPwdChangeAllowed(array $data, UserInterface $user, $errorExtension)
    {
        $form = $this->changePasswordForm;
        $form->setData($data);
        if (!$form->isValid()) {
            $this->flashMessenger->setNamespace(AccountController::ERROR_NAME_SPACE . $errorExtension)
                ->addMessage('Form not valid.');
            return false;
        }

        $data = $form->getData();

        if (!$user->hashPassword($user, $data['currentPassword'])) {
            $this->flashMessenger->setNamespace(AccountController::ERROR_NAME_SPACE . $errorExtension)
                ->addMessage('Wrong Password.');
            return false;
        }

        return true;
    }
}