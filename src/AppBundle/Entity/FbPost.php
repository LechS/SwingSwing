<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * FbPost
 *
 * @ORM\Table(name="fb_posts")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FbPostRepository")
 */
class FbPost
{
    const STATUS_NEW = 0;
    const STATUS_DRAFT = 1;
    const STATUS_TO_SEND = 2;
    const STATUS_SENDING = 3;

    const STATUSES = [
        self::STATUS_NEW => 'nowy',
        self::STATUS_DRAFT => 'draft',
        self::STATUS_TO_SEND => 'do wysłania',
        self::STATUS_SENDING => 'wysyłany',
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status = 0;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="fbPosts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\FbEndpoint", inversedBy="fbPosts")
     * @ORM\JoinTable(name="posts_endpoints")
     */
    private $fbEndpoints;

    public function __construct() {
        $this->fbEndpoints = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \stdClass $user
     *
     * @return FbPost
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set fbEndpoint
     *
     * @param \stdClass $fbEndpoint
     *
     * @return FbPost
     */
    public function setFbEndpoint($fbEndpoint)
    {
        $this->fbEndpoint = $fbEndpoint;

        return $this;
    }

    /**
     * Get fbEndpoint
     *
     * @return FbEndpoint
     */
    public function getFbEndpoint()
    {
        return $this->fbEndpoint;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return FbPost
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return FbPost
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return FbPost
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add fbEndpoint
     *
     * @param \AppBundle\Entity\FbEndpoint $fbEndpoint
     *
     * @return FbPost
     */
    public function addFbEndpoint(\AppBundle\Entity\FbEndpoint $fbEndpoint)
    {
        $this->fbEndpoints[] = $fbEndpoint;

        return $this;
    }

    /**
     * Remove fbEndpoint
     *
     * @param \AppBundle\Entity\FbEndpoint $fbEndpoint
     */
    public function removeFbEndpoint(\AppBundle\Entity\FbEndpoint $fbEndpoint)
    {
        $this->fbEndpoints->removeElement($fbEndpoint);
    }

    /**
     * Get fbEndpoints
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFbEndpoints()
    {
        return $this->fbEndpoints;
    }
}
