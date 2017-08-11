<?php

namespace PServerCore\View\Helper;

use PServerCore\Service\Timer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class TimerWidget extends AbstractHelper
{
    /** @var array */
    protected $timeCache;

    /** @var  array */
    protected $config;

    /** @var  Timer */
    protected $timeService;

    /**
     * TimerWidget constructor.
     * @param array $config
     * @param Timer $timeService
     */
    public function __construct(array $config, Timer $timeService)
    {
        $this->config = $config;
        $this->timeService = $timeService;
    }

    /**
     * @return string
     */
    public function __invoke()
    {
        $viewModel = new ViewModel([
            'timer' => $this->getTimer(),
        ]);
        $viewModel->setTemplate('helper/sidebarTimerWidget');

        return $this->getView()->render($viewModel);
    }

    /**
     * @return array
     */
    protected function getTimer()
    {
        if (!$this->timeCache) {
            if ($this->config) {
                foreach ($this->config as $data) {
                    $this->timeCache[] = $this->getTimeData($data);
                }
            }
        }

        return $this->timeCache;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function getTimeData(array $data)
    {
        $time = 0;
        $text = '';

        if (!isset($data['type'])) {
            if (isset($data['days'])) {
                $time = $this->timeService->getNextTimeDay(
                    $data['days'],
                    $data['hour'],
                    $data['min']
                );
            } else {
                $time = $this->timeService->getNextTime($data['hours'], $data['min']);
            }
        } else {
            $text = $data['time'];
        }

        $timerList = [];
        if (!empty($data['timers'])) {
            foreach ($data['timers'] as $timers) {
                $timerList[] = $this->getTimeData($timers);
            }
        }

        $result = [
            'time' => $time,
            'text' => $text,
            'name' => $data['name'],
            'icon' => $data['icon'],
        ];

        if ($timerList) {
            $result['timers'] = $timerList;
        }

        return $result;
    }
}