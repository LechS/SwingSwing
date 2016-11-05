<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * FbPage
 *
 * @ORM\Table(name="fb_pages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FbPageRepository")
 */
class FbPage
{
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
     * @ORM\Column(name="fb_id", type="string", length=255)
     */
    private $fbId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="confirmed", type="smallint")
     */
    private $confirmed = false;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="fbPages")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FbPost", mappedBy="fbPage")
     */
    private $fbPosts;


    public function __construct() {
        $this->fbPosts = new ArrayCollection();
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
     * Set fbId
     *
     * @param string $fbId
     *
     * @return FbPage
     */
    public function setFbId($fbId)
    {
        $this->fbId = $fbId;

        return $this;
    }

    /**
     * Get fbId
     *
     * @return string
     */
    public function getFbId()
    {
        return $this->fbId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return FbPage
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return FbPage
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Add fbPost
     *
     * @param \AppBundle\Entity\FbPost $fbPost
     *
     * @return FbPage
     */
    public function addFbPost(\AppBundle\Entity\FbPost $fbPost)
    {
        $this->fbPosts[] = $fbPost;

        return $this;
    }

    /**
     * Remove fbPost
     *
     * @param \AppBundle\Entity\FbPost $fbPost
     */
    public function removeFbPost(\AppBundle\Entity\FbPost $fbPost)
    {
        $this->fbPosts->removeElement($fbPost);
    }

    /**
     * Get fbPosts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFbPosts()
    {
        return $this->fbPosts;
    }

    /**
     * Set confirmed
     *
     * @param integer $confirmed
     *
     * @return FbPage
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get confirmed
     *
     * @return integer
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }
}
