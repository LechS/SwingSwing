<?php
namespace AppBundle\Service;

use AppBundle\Entity\FbPost;
use Doctrine\ORM\EntityManager;
use FacebookBundle\Service\FacebookService;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;



class RequestQueueService
{
    protected $em;
    protected $facebook;

    public function __construct(EntityManager $em, FacebookService $facebookService)
    {
        $this->em = $em;
        $this->facebook = $facebookService;
    }

    public function addPostToQueue(FbPost $post){



    }







}