<?php

namespace PServerCore\View\Helper;

use PServerCore\Service\Donate;
use Zend\View\Helper\AbstractHelper;

class DonateSum extends AbstractHelper
{
    /** @var  Donate */
    protected $donateService;

    /**
     * DonateSum constructor.
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
        return $this->donateService->getSumOfDonations();
    }

}