<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use GameBackend\DataService\DataServiceInterface;
use PServerCore\Entity\UserInterface;
use PServerCore\Options\EntityOptions;
use Zend\Form\FormInterface;

class Coin
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  DataServiceInterface */
    protected $gameBackendService;

    /** @var  EntityOptions */
    protected $entityOptions;

    /** @var  FormInterface */
    protected $adminCoinForm;

    /** @var  Ip */
    protected $ipService;

    /**
     * Coin constructor.
     * @param EntityManager $entityManager
     * @param DataServiceInterface $gameBackendService
     * @param EntityOptions $entityOptions
     * @param FormInterface $adminCoinForm
     * @param Ip $ipService
     */
    public function __construct(
        EntityManager $entityManager,
        DataServiceInterface $gameBackendService,
        EntityOptions $entityOptions,
        FormInterface $adminCoinForm,
        Ip $ipService
    ) {
        $this->entityManager = $entityManager;
        $this->gameBackendService = $gameBackendService;
        $this->entityOptions = $entityOptions;
        $this->adminCoinForm = $adminCoinForm;
        $this->ipService = $ipService;
    }

    /**
     * @param $data
     * @param $userId
     * @param UserInterface $adminUser
     * @return bool
     */
    public function addCoinsForm($data, $userId, UserInterface $adminUser)
    {
        $form = $this->adminCoinForm;
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        $user = $this->getUser4Id($userId);

        if ($user) {
            $data = $form->getData();
            $this->addCoins($user, $data['coins']);


            $class = $this->entityOptions->getDonateLog();
            /** @var \PServerCore\Entity\DonateLog $donateEntity */
            $donateEntity = new $class;
            $donateEntity->setTransactionId('AdminPanel: ' . $adminUser->getUsername())
                ->setCoins($data['coins'])
                ->setIp($this->ipService->getIp())
                ->setSuccess($donateEntity::STATUS_SUCCESS)
                ->setType($donateEntity::TYPE_INTERNAL)
                ->setUser($user);

            $this->entityManager->persist($donateEntity);
            $this->entityManager->flush();
        }

        return true;
    }

    /**
     * @param UserInterface $user
     * @return int
     */
    public function getCoinsOfUser(UserInterface $user)
    {
        return $this->gameBackendService->getCoins($user);
    }

    /**
     * @param UserInterface $user
     * @param               $amount
     * @return bool
     */
    public function addCoins(UserInterface $user, $amount)
    {
        return $this->gameBackendService->setCoins($user, $amount);
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

    /**
     * @return FormInterface
     */
    public function getAdminCoinForm()
    {
        return $this->adminCoinForm;
    }

}