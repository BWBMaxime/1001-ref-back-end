<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use App\Entity\Message;


class MessageController extends AbstractController
{
    /**
     * @Route("/message/send", name="sendMessage", methods={"POST"})
     */
    public function saveMessage(Request $request): Response
    {
        $form = $request->toArray();

        if($form['senderID'] == null || $form['targetID'] == null){
            return new Response(
                "Mauvaise requête",
                Response::HTTP_BAD_REQUEST,
                ['Access-Control-Allow-Origin' => '*']
            );
        }

        $message = new Message();
        $message->hydrate($form, $this->getDoctrine());

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($message);
        $entityManager->flush();

        return new Response(
            "Message sauvegardé!",
            Response::HTTP_OK,
            ['Access-Control-Allow-Origin' => '*']
        );
    }

    /**
     * @Route("/message/get/{user}/{target}", name="message", methods={"GET"})
     */
    public function getMessages(int $user, int $target): Response
    {
        
        $results = $this->getDoctrine()->getRepository(Message::class)->findConversations($user,$target);
    
        $result = $this->getSerializer()->serialize($results, 'json');

        return new Response(
            $result,
            Response::HTTP_OK,
            ['Access-Control-Allow-Origin' => '*']
        );
    }

    /**
     * @Route("/message/header/{user}", name="getHeaders", methods={"GET"})
     */
    public function getHeaders(int $user): Response
    {
        
            $results = $this->getDoctrine()->getRepository(Message::class)->findHeadersByUser($user);
    
            $result = $this->getSerializer()->serialize($results, 'json');

            return new Response(
                $result,
                Response::HTTP_OK,
                ['Access-Control-Allow-Origin' => '*']
            );

    }

    private function getSerializer(){
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
            return $object->getId();
        },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        return new Serializer([$normalizer], [$encoder]);
    }
}
