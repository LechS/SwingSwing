<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FacebookBundle\Service\FacebookService;

/**
 * FbEndpoint
 *
 * @ORM\Table(name="fb_endpoints")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FbEndpointRepository")
 */
class FbEndpoint
{
    const TYPES =[
//        FacebookService::TYPE_GROUP => 'grupa',
        FacebookService::TYPE_PAGE => 'fanpage',
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
     * @ORM\Column(name="fb_id", type="string", length=255, unique=true)
     */
    private $fbId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="string")
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\FbPost", mappedBy="fbEndpoints")
     */
    private $fbPosts;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="fbEndpoints")
     * @ORM\JoinTable(name="users_endpoints")
     */
    private $users;

    public function __construct() {
        $this->fbPosts = new ArrayCollection();
        $this->users = new ArrayCollection();
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
     * @return FbEndpoint
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
     * Set type
     *
     * @param integer $type
     *
     * @return FbEndpoint
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return FbEndpoint
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
     * Set city
     *
     * @param string $city
     *
     * @return FbEndpoint
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return FbEndpoint
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Add fbPost
     *
     * @param \AppBundle\Entity\FbPost $fbPost
     *
     * @return FbEndpoint
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
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return FbEndpoint
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\User $user
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
