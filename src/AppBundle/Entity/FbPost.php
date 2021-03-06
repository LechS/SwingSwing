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
    const STATUS_DELETED = -1;
    const STATUS_NEW = 0;
    const STATUS_DRAFT = 1;
    const STATUS_TO_SEND = 2;
    const STATUS_SENDING = 3;
    const STATUS_SEND = 1;

    const STATUSES = [
        self::STATUS_DELETED => 'usunięty',
        self::STATUS_NEW => 'nowy',
        self::STATUS_DRAFT => 'draft',
        self::STATUS_TO_SEND => 'do wysłania',
        self::STATUS_SENDING => 'wysyłany',
        self::STATUS_SEND => 'wysłany',
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=true)
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

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FbPage", inversedBy="fbPosts")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=true)
     */
    private $fbPage;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FbQueue", mappedBy="fbPost")
     */
    private $fbQueues;


    public function __construct() {
        $this->fbEndpoints = new ArrayCollection();
        $this->fbQueues = new ArrayCollection();
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

    public function setId($id){

        $this->id = $id;

        return $this;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User
     * $user
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


    /**
     * Set fbPage
     *
     * @param $fbPage
     *
     * @return FbPost
     */
    public function setFbPage($fbPage = null)
    {
        $this->fbPage = $fbPage;

        return $this;
    }

    /**
     * Get fbPage
     *
     * @return \AppBundle\Entity\FbPage
     */
    public function getFbPage()
    {
        return $this->fbPage;
    }

    /**
     * Add fbQueue
     *
     * @param \AppBundle\Entity\FbQueue $fbQueue
     *
     * @return FbPost
     */
    public function addFbQueue(\AppBundle\Entity\FbQueue $fbQueue)
    {
        $this->fbQueues[] = $fbQueue;

        return $this;
    }

    /**
     * Remove fbQueue
     *
     * @param \AppBundle\Entity\FbQueue $fbQueue
     */
    public function removeFbQueue(\AppBundle\Entity\FbQueue $fbQueue)
    {
        $this->fbQueues->removeElement($fbQueue);
    }

    /**
     * Get fbQueues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFbQueues()
    {
        return $this->fbQueues;
    }
}
