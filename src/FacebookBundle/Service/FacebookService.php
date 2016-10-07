<?php
namespace FacebookBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

use Facebook\Facebook;

class FacebookService
{
    protected $em;
    protected $user;
    protected $facebook;

    public function __construct(EntityManager $em, TokenStorage $tokenStorage, $appId, $appSecret)
    {
        $this->em = $em;
        $this->user= $tokenStorage->getToken()->getUser();

        $this->facebook = new Facebook([
            'app_id' => "{$appId}",
            'app_secret' => "{$appSecret}",
            'default_graph_version' => 'v2.7',
        ]);
    }
    
    public function setLongLivedAccessToken(){
        $facebook = $this->facebook;

        var_dump($facebook);die;

        die('asdasdas');
    }

}