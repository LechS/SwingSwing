<?php
namespace FacebookBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Entity\User;
use DateTime;

use Facebook\Facebook;
use Facebook\FacebookApp;
use Facebook\FacebookRequest;

class FacebookService
{
    protected $em;
    protected $facebook;
    protected $fbApp;

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

    public function checkPermissions(User $user){
        $fb = $this->facebook;

        $fb->setDefaultAccessToken($user->getFacebookLongLivedAccessToken());

        try {
            $response = $fb->get("/{$user->getFacebookId()}/permissions");
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $permissions = $response->getDecodedBody();

        foreach ($permissions['data'] as $perm){
            if($perm['status'] != 'granted'){
                return false;
            }
        }
        return true;
    }

    public function checkTokenExpirationDate(User $user){

        $now = new DateTime('now');
        $tokenExpirationDate = $user->getLlTokenExpirationDate();

        if($tokenExpirationDate < $now){
            return false;
        }else{
            return true;
        }
    }
    
    public function publicationTest(User $user){
        $fb = $this->facebook;
        $fb->setDefaultAccessToken($user->getFacebookLongLivedAccessToken());

        $linkData = [
            'link' => 'http://www.example.com',
            'message' => 'User provided message',
        ];

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->post('/{id}/feed', $linkData);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $graphNode = $response->getGraphNode();

        var_dump($graphNode);die;

        die('ok');
    }

    public function search(User $user, $searchString, $type = null){
        $fb = $this->facebook;
        $fbApp = $this->fbApp;

        $searchString = 'shimsham';

        $searchArray =   array (
            'type' => 'group',
            'q' => $searchString,
            'fields' => 'id,name,privacy,locale',
        );

        $request = new FacebookRequest($fbApp, $user->getFacebookLongLivedAccessToken(), 'GET', '/search', $searchArray);

// Send the request to Graph
        try {
            $response = $fb->getClient()->sendRequest($request);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        var_dump($response->getDecodedBody());die;

        return $response->getDecodedBody();
    }

}