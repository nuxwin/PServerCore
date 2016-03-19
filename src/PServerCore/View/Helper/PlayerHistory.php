<?php

namespace PServerCore\View\Helper;

use PServerCore\Options\GeneralOptions;
use PServerCore\Service\PlayerHistory as PlayerHistoryService;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class PlayerHistory extends AbstractHelper
{
    /** @var  PlayerHistoryService */
    protected $playerHistoryService;

    /** @var  GeneralOptions */
    protected $generalOptions;

    /**
     * PlayerHistory constructor.
     * @param PlayerHistoryService $playerHistoryService
     * @param GeneralOptions $generalOptions
     */
    public function __construct(PlayerHistoryService $playerHistoryService, GeneralOptions $generalOptions)
    {
        $this->playerHistoryService = $playerHistoryService;
        $this->generalOptions = $generalOptions;
    }


    /**
     * @param bool|false $showView
     * @return string
     */
    public function __invoke($showView = false)
    {
        $currentPlayer = $this->playerHistoryService->getCurrentPlayer();
        $result = $currentPlayer;

        if ($showView) {
            $viewModel = new ViewModel([
                'currentPlayer' => $currentPlayer,
                'maxPlayer' => $this->generalOptions->getMaxPlayer(),
            ]);
            $viewModel->setTemplate('helper/playerHistory');

            $result = $this->getView()->render($viewModel);
        }

        return $result;
    }
}