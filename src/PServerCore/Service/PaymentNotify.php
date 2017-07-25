<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use Exception;
use PaymentAPI\Provider\Request;
use PaymentAPI\Service\AlreadyAddedException;
use PaymentAPI\Service\LogInterface;
use PServerCore\Entity\DonateLog;
use PServerCore\Options\Collection;

class PaymentNotify implements LogInterface
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Collection */
    protected $collectionOptions;

    /** @var  Coin */
    protected $coinService;

    /** @var  UserBlock */
    protected $userBlockService;

    /** @var  PaymentNotifyCoins */
    protected $paymentNotifyCoins;

    /**
     * PaymentNotify constructor.
     * @param EntityManager $entityManager
     * @param Collection $collectionOptions
     * @param Coin $coinService
     * @param UserBlock $userBlockService
     * @param PaymentNotifyCoins $paymentNotifyCoins
     */
    public function __construct(
        EntityManager $entityManager,
        Collection $collectionOptions,
        Coin $coinService,
        UserBlock $userBlockService,
        PaymentNotifyCoins $paymentNotifyCoins
    ) {
        $this->entityManager = $entityManager;
        $this->collectionOptions = $collectionOptions;
        $this->coinService = $coinService;
        $this->userBlockService = $userBlockService;
        $this->paymentNotifyCoins = $paymentNotifyCoins;
    }

    /**
     * Method the add the reward
     *
     * @param Request $request
     * @return boolean
     * @throws \Exception
     */
    public function success(Request $request)
    {
        $user = $this->getUser4Id($request->getUserId());
        if (!$user) {
            throw new \Exception('User not found');
        }

        // we already added add the reward, so skip this =)
        if ($this->isStatusSuccess($request) && $this->isDonateAlreadyAdded($request)) {
            // no exception, or the other side spam the service ...
            throw new AlreadyAddedException('already added');
        }

        // check if donate should add coins or remove
        $request->setAmount(abs($request->getAmount()));
        $coins = $this->isStatusSuccess($request) ? $request->getAmount() : -$request->getAmount();
        $request->setAmount($this->paymentNotifyCoins->getAmount($user, (int)$coins));

        // save the message if gamebackend-service is unavailable
        $errorMessage = '';
        try {
            $this->coinService->addCoins($user, $request->getAmount());
        } catch (\Exception $e) {
            $request->setStatus($request::STATUS_ERROR);
            $errorMessage = $e->getMessage();
        }

        if ($request->isReasonToBan()) {
            $expire = (int)$this->collectionOptions->getConfig()['payment-api']['ban-time'] + time();
            $reason = 'Donate - ChargeBack';

            $this->userBlockService->blockUser($user, $expire, $reason);
        }

        $this->saveDonateLog($request, $user, $errorMessage);

        return true;
    }

    /**
     * Method to log the error
     *
     * @param Request $request
     * @param Exception $e
     * @return bool
     */
    public function error(Request $request, Exception $e)
    {
        $user = $this->getUser4Id($request->getUserId());

        $this->saveDonateLog($request, $user, $e->getMessage());

        return true;
    }

    /**
     * @param Request $request
     * @param $user
     * @param string $errorMessage
     * @return DonateLog
     */
    protected function getDonateLogEntity4Data(Request $request, $user, $errorMessage = '')
    {
        $data = $request->toArray();
        if ($errorMessage) {
            $data['errorMessage'] = $errorMessage;
        }

        $class = $this->collectionOptions->getEntityOptions()->getDonateLog();
        /** @var DonateLog $donateEntity */
        $donateEntity = new $class;
        $donateEntity->setTransactionId($request->getTransactionId())
            ->setCoins($request->getAmount())
            ->setIp($request->getIp())
            ->setSuccess($request->getStatus())
            ->setType($this->mapPaymentProvider2DonateType($request))
            ->setDesc(json_encode($data));

        if ($user) {
            $donateEntity->setUser($user);
        }

        return $donateEntity;
    }

    /**
     * @param Request $request
     * @param $user
     * @param string $errorMessage
     */
    protected function saveDonateLog(Request $request, $user, $errorMessage = '')
    {
        $donateLog = $this->getDonateLogEntity4Data($request, $user, $errorMessage);
        $this->entityManager->persist($donateLog);
        $this->entityManager->flush();
    }

    /**
     * Helper to map the PaymentProvider 2 DonateType
     *
     * @param Request $request
     * @return string
     */
    protected function mapPaymentProvider2DonateType(Request $request)
    {
        $result = '';
        switch ($request->getProvider()) {
            case Request::PROVIDER_PAYMENT_WALL:
                $result = DonateLog::TYPE_PAYMENT_WALL;
                break;
            case Request::PROVIDER_SUPER_REWARD:
                $result = DonateLog::TYPE_SUPER_REWARD;
                break;
            case Request::PROVIDER_XSOLLA:
                $result = DonateLog::TYPE_XSOLLA;
                break;
            case Request::PROVIDER_PAY_PAL:
                $result = DonateLog::TYPE_PAY_PAL;
                break;
            case Request::PROVIDER_SOFORT:
                $result = DonateLog::TYPE_SOFORT;
                break;
        }

        return $result;
    }

    /**
     * check is donate already added, if the provider ask, more than 1 time, this only works with a transactionId
     *
     * @param Request $request
     * @return bool
     */
    protected function isDonateAlreadyAdded(Request $request)
    {
        /** @var \PServerCore\Entity\Repository\DonateLog $donateEntity */
        $donateEntity = $this->entityManager->getRepository($this->collectionOptions->getEntityOptions()->getDonateLog());

        return $donateEntity->isDonateAlreadyAdded($request->getTransactionId(),
            $this->mapPaymentProvider2DonateType($request));
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isStatusSuccess(Request $request)
    {
        return $request->getStatus() == $request::STATUS_SUCCESS;
    }

    /**
     * @param int $userId
     *
     * @return null|\PServerCore\Entity\UserInterface|\SmallUser\Entity\UserInterface
     */
    protected function getUser4Id($userId)
    {
        /** @var \PServerCore\Entity\Repository\User $userRepository */
        $userRepository = $this->entityManager->getRepository($this->collectionOptions->getEntityOptions()->getUser());

        return $userRepository->getUser4Id($userId);
    }
}