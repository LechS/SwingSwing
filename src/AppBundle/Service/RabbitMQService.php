<?php
namespace AppBundle\Service;

use AppBundle\Entity\FbPost;
use Doctrine\ORM\EntityManager;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use PhpAmqpLib\Message\AMQPMessage;



class RabbitMQService implements ConsumerInterface
{
    protected $em;
    protected $rabbitMQProducer;
    protected $queueService;

    public function __construct(EntityManager $em, Producer $rabbitProducer, RequestQueueService $queueService)
    {
        $this->em = $em;
        $this->rabbitMQProducer = $rabbitProducer;
        $this->queueService = $queueService;
    }

    public function execute(AMQPMessage $msg)
    {
//        //Process picture upload.
//        //$msg will be an instance of `PhpAmqpLib\Message\AMQPMessage` with the $msg->body being the data sent over RabbitMQ.
//
//        if (!$isUploadSuccess) {
//            $isUploadSuccess = someUploadPictureMethod();
//            // If your image upload failed due to a temporary error you can return false
//            // from your callback so the message will be rejected by the consumer and
//            // requeued by RabbitMQ.
//            // Any other value not equal to false will acknowledge the message and remove it
//            // from the queue
//            return false;
//        }

        $data = unserialize($msg->getBody());
        $postId = $data['post_id'];

        return true;
    }


    public function addPostToRabbitQueue(FbPost $post){
        $msg = array('post_id' => $post->getId());
        $this->rabbitMQProducer->publish(serialize($msg));
    }



}