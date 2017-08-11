<?php

namespace PServerCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use PServerCore\Keys\Caching;
use PServerCore\Service\ServiceManager;

/**
 * PLAYER_HISTORY
 * @ORM\Table(name="player_history")
 * @ORM\Entity(repositoryClass="PServerCore\Entity\Repository\PlayerHistory")
 * @ORM\HasLifecycleCallbacks
 */
class PlayerHistory
{

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(name="player", type="smallint", nullable=false)
     */
    private $player;

    /**
     * @var \DateTime
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * PlayerHistory constructor.
     */
    public function __construct()
    {
        $this->created = new \DateTime();
    }

    /**
     * @ORM\PreFlush()
     */
    public function preFlush()
    {
        /** @var \PServerCore\Service\CachingHelper $cachingHelperService */
        $cachingHelperService = ServiceManager::getInstance()->get('pserver_cachinghelper_service');
        $cachingHelperService->delItem(Caching::PLAYER_HISTORY);
    }

    /**
     * @ORM\PostRemove()
     */
    public function postRemove()
    {
        /** @var \PServerCore\Service\CachingHelper $cachingHelperService */
        $cachingHelperService = ServiceManager::getInstance()->get('pserver_cachinghelper_service');
        $cachingHelperService->delItem(Caching::PLAYER_HISTORY);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param int $player
     * @return PlayerHistory
     */
    public function setPlayer($player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return PlayerHistory
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }
} 