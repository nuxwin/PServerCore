<?php

namespace PServerCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use PServerCore\Keys\Caching;
use PServerCore\Service\ServiceManager;

/**
 * NEWS
 * @ORM\Table(name="news", indexes={@ORM\Index(name="fk_news_users1_idx", columns={"users_usrId"})})
 * @ORM\Entity(repositoryClass="PServerCore\Entity\Repository\News")
 */
class News
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
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="memo", type="text", nullable=false)
     */
    private $memo;

    /**
     * @var int
     * @ORM\Column(name="active", type="smallint", nullable=false)
     */
    private $active;

    /**
     * @var \DateTime
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var UserInterface
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="users_usrId", referencedColumnName="usrId")
     * })
     */
    private $user;

    /**
     * News constructor.
     */
    public function __construct()
    {
        $this->created = new \DateTime();
    }

    /**
     * Get nid
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set title
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set memo
     * @param string $memo
     * @return self
     */
    public function setMemo($memo)
    {
        $this->memo = $memo;

        return $this;
    }

    /**
     * Get memo
     * @return string
     */
    public function getMemo()
    {
        return $this->memo;
    }

    /**
     * Set active
     * @param string $active
     * @return self
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set created
     * @param \DateTime $created
     * @return self
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set user
     * @param UserInterface $user
     * @return self
     */
    public function setUser(UserInterface $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }


}
