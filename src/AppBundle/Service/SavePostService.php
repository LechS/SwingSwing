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

    public function editPost(User $user, ParameterBag $post, FbPost $fbPost){
        $message = $post->get('message');
        $link = $post->get('link');
        $newEndpoints = $post->get('checkedValues');
        $oldEndpoints = $fbPost->getFbEndpoints();

        $fbPost->setLink($link);
        $fbPost->setMessage($message);

        foreach ($oldEndpoints as $endpoint ){
            $fbPost->removeFbEndpoint($endpoint);
            $this->em->persist($endpoint);
        }
        $this->em->persist($fbPost);

        if(!empty($newEndpoints)) {
            foreach ($newEndpoints as $fbId) {
                $endpoint = $this->em->getRepository('AppBundle:FbEndpoint')->findOneBy(['fbId' => $fbId]);
                $fbPost->addFbEndpoint($endpoint);

                $this->em->persist($endpoint);
            }
        }

        $this->em->persist($fbPost);
        $this->em->flush();


        return true;
    }

    public function similarPost(FbPost $fbPost){

        $newFbPost = clone $fbPost;
        $newFbPost->setId(null);
        $newFbPost->setStatus(FbPost::STATUS_NEW);

        $this->em->persist($newFbPost);
        $this->em->flush();

        return $newFbPost;

    }

}