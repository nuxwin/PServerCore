<?php


namespace PServerCore\Service;

use PaymentAPI\Service\Validation;

class PaymentValidation extends Validation
{
    /** @var  User */
    protected $userService;

    /**
     * PaymentValidation constructor.
     * @param User $userService
     */
    public function __construct(User $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function userExists($userId)
    {
        $user = $this->userService->getUser4Id($userId);
        return (bool)$user;
    }

}