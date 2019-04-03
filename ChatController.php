<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    /**
     * @Route("/chat", name="chat")
     */
    public function index(MessageRepository $messageRepository)
    {


        $messages = $messageRepository->findAll();
        $response = $this->render('chat/index.html.twig', [
            'messages' => $messages
        ]);

        return $response;
    }

    /**
     * @Route("/post", name="post_chat", methods={"POST"})
     */
    public function post(EntityManagerInterface $entityManager, Publisher $publisher, Request $request)
    {
        $texte = $request->request->get('texte');

        $message = new Message();
        $message->setCreated(new DateTime('now'));
        $message->setMessage($texte);
        $message->setUser($this->getUser());
        $entityManager->persist($message);
        $entityManager->flush();

        $update = new Update(
            'http://chatlp.com/message',
            json_encode(['message' => $texte,
                'username' => $this->getUser()->getUsername(),
                'created' => $message->getCreated()->format('d/m/Y H:i')])
           // ['http://chatlp.com/message/user1', 'http://chatlp.com/message/user2']
        );

        // Sync, or async (RabbitMQ, Kafka...)
        $publisher($update);

        return $this->json(true, Response::HTTP_OK);
    }
}
