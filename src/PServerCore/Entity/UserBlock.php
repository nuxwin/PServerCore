<?php

namespace PServerCore\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserBlock
 * @ORM\Table(name="user_block", indexes={@ORM\Index(name="fk_userBlock_users1_idx", columns={"users_usrId"})})
 * @ORM\Entity(repositoryClass="PServerCore\Entity\Repository\UserBlock")
 */
class UserBlock
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="reason", type="text", nullable=false)
     */
    private $reason;

    /**
     * @var DateTime
     * @ORM\Column(name="expire", type="datetime", nullable=false)
     */
    private $expire;

    /**
     * @var DateTime
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var UserInterface
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="users_usrId", referencedColumnName="usrId", nullable=false)
     * })
     */
    private $user;

    /**
     * @var UserInterface
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creator_usrId", referencedColumnName="usrId")
     * })
     */
    private $creator;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->created = new DateTime();
    }

    /**
     * Get bid
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set reason
     * @param string $reason
     * @return self
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set expire
     * @param $expire
     * @return self
     */
    public function setExpire($expire)
    {
        if (!$expire instanceof DateTime) {
            if (is_integer($expire)) {
                $expire = (new DateTime())->setTimestamp($expire);
            } else {
                $expire = new DateTime($expire);
            }
        }
        $this->expire = $expire;

        return $this;
    }

    /**
     * Get expire
     * @return DateTime
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * Set created
     * @param DateTime $created
     * @return self
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param UserInterface $user
     * @return $this
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return UserInterface
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param UserInterface $creator
     * @return self
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
        return $this;
    }


}
