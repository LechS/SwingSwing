<?php
namespace FacebookBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use AppBundle\Entity\User;
use DateTime;

use Facebook\Facebook;
use Facebook\FacebookApp;
use Facebook\FacebookRequest;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;

class FacebookService
{
    protected $em;

    protected $facebook;

    protected $fbApp;

    const TYPE_GROUP = 'group';
    const TYPE_PAGE = 'page';

    const VALID_SEARCH_TYPES =[
        self::TYPE_PAGE,
        self::TYPE_GROUP,
    ];

    /**
     * FacebookService constructor.
     * @param EntityManager $em
     * @param $appId
     * @param $appSecret
     */
    public function __construct(EntityManager $em, $appId, $appSecret)
    {
        $this->em = $em;

        $this->facebook = new Facebook([
            'app_id' => "{$appId}",
            'app_secret' => "{$appSecret}",
            'default_graph_version' => 'v2.7',
        ]);

        $this->fbApp = new FacebookApp("{$appId}", "{$appSecret}");
    }

    /**
     * @param User $user
     */
    public function setLongLivedAccessToken(User $user){

        $facebook = $this->facebook;
        // OAuth 2.0 client handler
        $oAuth2Client = $facebook->getOAuth2Client();
        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken("{$user->getFacebookAccessToken()}");
        $user->setFacebookLongLivedAccessToken($longLivedAccessToken->getValue());
        $user->setLlTokenExpirationDate($longLivedAccessToken->getExpiresAt());
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     * @return array
     */
    public function getPermissions(User $user){
        $fb = $this->facebook;

        $fb->setDefaultAccessToken($user->getFacebookLongLivedAccessToken());

        try {
            $response = $fb->get("/{$user->getFacebookId()}/permissions");
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        return $response->getDecodedBody();
    }

    /**
     * @param array $permissions
     * @return bool
     */
    public function checkPermissions(array $permissions){

        foreach ($permissions['data'] as $perm){
            if($perm['status'] != 'granted'){
                return false;
            }
        }
        return true;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function checkTokenExpirationDate(User $user){

        $now = new DateTime('now');
        $tokenExpirationDate = $user->getLlTokenExpirationDate();

        if($tokenExpirationDate < $now){
            return false;
        }else{
            return true;
        }
    }

    /**
     * @param User $user
     * @param $id
     * @param null $message
     * @param null $link
     * @return \Facebook\GraphNodes\GraphNode
     */
    public function publish(User $user, $id, $message = null, $link = null){
        $fb = $this->facebook;
        $fb->setDefaultAccessToken($user->getFacebookLongLivedAccessToken());

        $data = [
            'link' => $link,
            'message' => $message,
        ];

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->post("/{$id}/feed", $data);
        } catch(FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        return $response->getGraphNode();
    }

    /**
     * @param User $user
     * @param $searchString
     * @param $type
     * @return array
     */
    public function search(User $user, $searchString, $type){
        $fb = $this->facebook;
        $fbApp = $this->fbApp;

        if(!in_array($type, self::VALID_SEARCH_TYPES)){
            throw new Exception('invalid search type');
        }

        $searchArray =   array (
            'type' => $type,
            'q' => $searchString,
            'fields' => 'id,name,privacy,locale',
        );

        $request = new FacebookRequest($fbApp, $user->getFacebookLongLivedAccessToken(), 'GET', '/search', $searchArray);

// Send the request to Graph
        try {
            $response = $fb->getClient()->sendRequest($request);
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        return $response->getDecodedBody();
    }

}