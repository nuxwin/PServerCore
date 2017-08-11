<?php

namespace PServerCore\View\Helper;

use PServerCore\Service\Donate;
use Zend\View\Helper\AbstractHelper;

class DonateCounter extends AbstractHelper
{
    /** @var  Donate */
    protected $donateService;

    /**
     * DonateCounter constructor.
     * @param Donate $donateService
     */
    public function __construct(Donate $donateService)
    {
        $this->donateService = $donateService;
    }

    /**
     * @return int
     */
    public function __invoke()
    {
        return $this->donateService->getNumberOfDonations();
    }
}