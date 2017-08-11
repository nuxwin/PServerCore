<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use PServerCore\Entity\UserCodes as UserCodesEntity;
use PServerCore\Entity\UserInterface;
use PServerCore\Options\Collection;
use PServerCore\Service\UserCodes as UserCodesService;
use Zend\Authentication\AuthenticationService;
use Zend\Form\FormInterface;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\Mvc\Controller\PluginManager;

class AddEmail
{
    const ERROR_NAMESPACE = 'pserver-user-account-errorAddEmail';
    const SUCCESS_NAMESPACE = 'pserver-user-account-successAddEmail';

    /** @var PluginManager */
    protected $controllerPluginManager;

    /** @var  Collection */
    protected $collectionOptions;

    /** @var  FormInterface */
    protected $addEmailForm;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Mail */
    protected $mailService;

    /** @var  UserCodesService */
    protected $userCodeService;

    /** @var  AuthenticationService */
    protected $authService;

    /**
     * AddEmail constructor.
     * @param AuthenticationService $authService
     * @param PluginManager $controllerPluginManager
     * @param Collection $collectionOptions
     * @param FormInterface $addEmailForm
     * @param EntityManager $entityManager
     * @param Mail $mailService
     * @param UserCodesService $userCodeService
     */
    public function __construct(
        AuthenticationService $authService,
        PluginManager $controllerPluginManager,
        Collection $collectionOptions,
        FormInterface $addEmailForm,
        EntityManager $entityManager,
        Mail $mailService,
        UserCodesService $userCodeService
    ) {
        $this->authService = $authService;
        $this->controllerPluginManager = $controllerPluginManager;
        $this->collectionOptions = $collectionOptions;
        $this->addEmailForm = $addEmailForm;
        $this->entityManager = $entityManager;
        $this->mailService = $mailService;
        $this->userCodeService = $userCodeService;
    }


    /**
     * @param $data
     * @param UserInterface $user
     * @return bool
     */
    public function addEmail($data, UserInterface $user)
    {
        $form = $this->getAddEmailForm();
        $form->setData($data);
        if (!$form->isValid()) {
            foreach ($form->getElements() as $messages) {
                /** @var \Zend\Form\ElementInterface $messages */
                foreach ($messages->getMessages() as $message) {
                    $this->getFlashMessenger()
                        ->setNamespace(self::ERROR_NAMESPACE)
                        ->addMessage($message);
                }
            }

            return false;
        }

        if ($user->getEmail()) {
            $this->getFlashMessenger()
                ->setNamespace(self::ERROR_NAMESPACE)
                ->addMessage('Email already exists in your Account');
            return false;
        }

        $data = $form->getData();
        $entityManager = $this->entityManager;

        if ($this->collectionOptions->getRegisterOptions()->isMailConfirmation()) {
            $userExtensionName = $this->collectionOptions->getEntityOptions()->getUserExtension();
            /** @var \PServerCore\Entity\UserExtension $userExtension */
            $userExtension = new $userExtensionName;

            /** @var \PServerCore\Entity\Repository\UserExtension $extensionRepository */
            $extensionRepository = $entityManager->getRepository($userExtensionName);
            $extensionRepository->deleteExtension($user, $userExtension::KEY_ADD_EMAIL);

            $userExtension->setKey($userExtension::KEY_ADD_EMAIL)
                ->setUser($user)
                ->setValue($data['email']);

            $entityManager->persist($userExtension);
            $entityManager->flush();

            $code = $this->userCodeService->setCode4User($user, UserCodesEntity::TYPE_ADD_EMAIL);
            $user->setEmail($data['email']);

            $this->mailService->addEmail($user, $code);

            $this->getFlashMessenger()
                ->setNamespace(self::SUCCESS_NAMESPACE)
                ->addMessage('Success, please confirm your mail.');
        } else {
            $user->setEmail($data['email']);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->authService->getStorage()->write($user);
        }

        return true;
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function changeMail(UserInterface $user)
    {
        $entityManager = $this->entityManager;
        $userExtensionName = $this->collectionOptions->getEntityOptions()->getUserExtension();
        /** @var \PServerCore\Entity\UserExtension $userExtension */
        $userExtension = new $userExtensionName;
        /** @var \PServerCore\Entity\Repository\UserExtension $extensionRepository */
        $extensionRepository = $entityManager->getRepository($userExtensionName);
        $userExtension = $extensionRepository->findOneBy(
            [
                'key' => $userExtension::KEY_ADD_EMAIL,
                'user' => $user
            ]
        );

        $user->setEmail($userExtension->getValue());
        $entityManager->persist($user);
        $entityManager->flush();

        $extensionRepository->deleteExtension($user, $userExtension::KEY_ADD_EMAIL);

        return $user;
    }

    /**
     * @return FormInterface
     */
    public function getAddEmailForm()
    {
        return $this->addEmailForm;
    }

    /**
     * @return FlashMessenger
     */
    protected function getFlashMessenger()
    {
        return $this->controllerPluginManager->get('flashMessenger');
    }

}