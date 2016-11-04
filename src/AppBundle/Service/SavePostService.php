<?php
namespace AppBundle\Service;

use AppBundle\Entity\FbPost;
use Doctrine\ORM\EntityManager;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\ParameterBag;


class SavePostService
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function savePost(User $user, ParameterBag $post){

        $message = $post->get('message');
        $link = $post->get('link');
        $endpoints = $post->get('checkedValues');

        $post = new FbPost();

        $post->setLink($link);
        $post->setMessage($message);
        $post->setUser($user);

        foreach ($endpoints as $fbId){
            $endpoint = $this->em->getRepository('AppBundle:FbEndpoint')->findOneBy(['fbId' => $fbId]);
            $post->addFbEndpoint($endpoint);

            $this->em->persist($endpoint);
        }
        $this->em->persist($post);
        $this->em->flush();

        return true;
    }


}