<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use AppBundle\Entity\Traits\Timestamps;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_users")
 */
class User extends BaseUser
{
    use Timestamps;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebook_id;
    
    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebook_access_token;    
    
    /** @ORM\Column(name="facebook_long_lived_access_token", type="string", length=255, nullable=true) */
    protected $facebook_long_lived_access_token;

    /**
     * @ORM\Column(name="ll_token_expiration_date", type="datetime", nullable=true)
     * @var \DateTime
     */
    private $llTokenExpirationDate;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FbPost", mappedBy="user")
     */
    private $fbPosts;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FbPage", mappedBy="user")
     */
    private $fbPages;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\FbEndpoint", mappedBy="users")
     */
    private $fbEndpoints;
    
    public function __construct()
    {
        parent::__construct();
        $this->fbEndpoints = new ArrayCollection();
    }


    /**
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebookAccessToken
     *
     * @param string $facebookAccessToken
     *
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebookAccessToken
     *
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Set facebookLongLivedAccessToken
     *
     * @param string $facebookLongLivedAccessToken
     *
     * @return User
     */
    public function setFacebookLongLivedAccessToken($facebookLongLivedAccessToken)
    {
        $this->facebook_long_lived_access_token = $facebookLongLivedAccessToken;

        return $this;
    }

    /**
     * Get facebookLongLivedAccessToken
     *
     * @return string
     */
    public function getFacebookLongLivedAccessToken()
    {
        return $this->facebook_long_lived_access_token;
    }

    /**
     * Set llTokenExpirationDate
     *
     * @param \DateTime $llTokenExpirationDate
     *
     * @return User
     */
    public function setLlTokenExpirationDate($llTokenExpirationDate)
    {
        $this->llTokenExpirationDate = $llTokenExpirationDate;

        return $this;
    }

    /**
     * Get llTokenExpirationDate
     *
     * @return \DateTime
     */
    public function getLlTokenExpirationDate()
    {
        return $this->llTokenExpirationDate;
    }

    /**
     * Add fbPost
     *
     * @param \AppBundle\Entity\FbPost $fbPost
     *
     * @return User
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
     * Add fbEndpoint
     *
     * @param \AppBundle\Entity\FbEndpoint $fbEndpoint
     *
     * @return User
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
     * Add fbPage
     *
     * @param \AppBundle\Entity\FbPage $fbPage
     *
     * @return User
     */
    public function addFbPage(\AppBundle\Entity\FbPage $fbPage)
    {
        $this->fbPages[] = $fbPage;

        return $this;
    }

    /**
     * Remove fbPage
     *
     * @param \AppBundle\Entity\FbPage $fbPage
     */
    public function removeFbPage(\AppBundle\Entity\FbPage $fbPage)
    {
        $this->fbPages->removeElement($fbPage);
    }

    /**
     * Get fbPages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFbPages()
    {
        return $this->fbPages;
    }
}
