<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use PServerCore\Entity\UserInterface;
use PServerCore\Options\EntityOptions;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\PluginManager;

class UserRole
{
    const ERROR_NAME_SPACE = 'p-server-admin-user-panel';

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  FormInterface */
    protected $adminUserRoleForm;

    /** @var  EntityOptions */
    protected $entityOptions;

    /** @var  PluginManager */
    protected $controllerPluginManager;

    /**
     * UserRole constructor.
     * @param EntityManager $entityManager
     * @param FormInterface $adminUserRoleForm
     * @param EntityOptions $entityOptions
     * @param PluginManager $controllerPluginManager
     */
    public function __construct(
        EntityManager $entityManager,
        FormInterface $adminUserRoleForm,
        EntityOptions $entityOptions,
        PluginManager $controllerPluginManager
    ) {
        $this->entityManager = $entityManager;
        $this->adminUserRoleForm = $adminUserRoleForm;
        $this->entityOptions = $entityOptions;
        $this->controllerPluginManager = $controllerPluginManager;
    }


    /**
     * @param $data
     * @param $userId
     * @return \PServerCore\Entity\UserInterface|bool
     */
    public function addRoleForm($data, $userId)
    {
        /** @var FormInterface $form */
        $form = $this->adminUserRoleForm;
        $form->setData($data);

        if (!$form->isValid()) {
            $this->addFormMessagesInFlashMessenger($form, $this::ERROR_NAME_SPACE);

            return false;
        }

        $data = $form->getData();
        $roleId = $data['roleId'];

        $user = $this->getUser4Id($userId);

        if ($user) {
            $this->addRole4User($user, $roleId);
        }

        return $user;
    }

    /**
     * @param $userId
     * @param $roleId
     */
    public function removeRole($userId, $roleId)
    {
        $user = $this->getUser4Id($userId);
        $this->removeRole4User($user, $roleId);
    }

    /**
     * @return FormInterface
     */
    public function getAdminUserRoleForm()
    {
        return $this->adminUserRoleForm;
    }

    /**
     * @param UserInterface $user
     * @param               $roleId
     * @return bool
     */
    protected function removeRole4User(UserInterface $user, $roleId)
    {
        $role = $this->getRoleEntity4Id($roleId);
        if (!$role) {
            return false;
        }

        $user->removeUserRole($role);
        $role->removeUser($user);
        $this->entityManager->persist($role);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param UserInterface $user
     * @param               $roleId
     * @return bool
     */
    protected function addRole4User(UserInterface $user, $roleId)
    {
        $result = false;

        if (!$this->isRoleAlreadyAdded($user, $roleId)) {
            $role = $this->getRoleEntity4Id($roleId);
            $role->addUser($user);
            $user->addUserRole($role);
            $this->entityManager->persist($role);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $result = true;
        }

        return $result;
    }

    /**
     * @param $roleId
     * @return null|\PServerCore\Entity\UserRole
     */
    protected function getRoleEntity4Id($roleId)
    {
        /** @var \PServerCore\Entity\Repository\UserRole $repository */
        $repository = $this->entityManager->getRepository($this->entityOptions->getUserRole());
        return $repository->getRole4Id($roleId);
    }

    /**
     * @param UserInterface $user
     * @param               $roleId
     * @return bool
     */
    protected function isRoleAlreadyAdded(UserInterface $user, $roleId)
    {
        $result = false;
        foreach ($user->getRoles() as $role) {
            if ($role->getId() == $roleId) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * @return \Zend\Mvc\Controller\Plugin\FlashMessenger
     */
    protected function getFlashMessenger()
    {
        return $this->controllerPluginManager->get('flashMessenger');
    }

    /**
     * @param FormInterface $form
     * @param $namespace
     */
    protected function addFormMessagesInFlashMessenger(FormInterface $form, $namespace)
    {
        $messages = $form->getMessages();
        foreach ($messages as $elementMessages) {
            foreach ($elementMessages as $message) {
                $this->getFlashMessenger()->setNamespace($namespace)->addMessage($message);
            }
        }
    }

    /**
     * @param $userId
     *
     * @return null|\PServerCore\Entity\UserInterface
     */
    protected function getUser4Id($userId)
    {
        /** @var \PServerCore\Entity\Repository\User $userRepository */
        $userRepository = $this->entityManager->getRepository($this->entityOptions->getUser());

        return $userRepository->getUser4Id($userId);
    }
}