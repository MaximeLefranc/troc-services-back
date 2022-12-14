<?php

namespace App\Controller\Api;

use App\Entity\Messages;
use App\Repository\MessagesRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MessagesController extends ApiController
{
    /** show all received
     * @Route("/api/user/{id<\d+>}/messages", name="browse_messages", methods={"GET"})
     */
    public function browseMessageById($id, MessagesRepository $messagesRepository, UserRepository $userRepository)
    {
      //  find all messages from the user
      $user =  $userRepository->findoneBy([
            'id' => $id
            
    
        ]);

        // return json content
        return $this->json200($messagesRepository->findMessagesByReceiver(
            [
                
                'receiver' => $user->getReceiver()
            ]

        ), [
      "groups" => 'message_browse', 
      'user_read_message'
     
    ]);
    }

    /** send a message
     * @Route("/api/messages/send", name="send_message", methods={"POST"})
     */
    public function sendMessage(Request $request, SerializerInterface $serializerInterface, ValidatorInterface $validatorInterface,
    EntityManagerInterface $em)
    {
        $content = $request->getContent();

        try{
            $message = $serializerInterface->deserialize($content, Messages::class, 'json');
            
            $date = new DateTime();
            $date->format('Y-m-d H:i:s');
            $message->setSentAt($date);
            $message->setSender($this->getUser()); //add the sender of the message to the db
    

    $errors = $validatorInterface->validate($message);

    if(count($errors)> 0){
        return $this->json($errors, 400);
    }
    
    $em->persist($message);
 
    $em->flush();
 } catch (NotEncodableValueException $e  )
 {
    return $this->json([
        'status' => '400',
        'message' => $e->getMessage()
    ],400);
 }
    
    //return the correct http response

    return $this->json(
        $message,
        Response::HTTP_CREATED,

        [],
        [
            // list of groups to use
            "groups" => 'message_browse',
            "advertisement_browse",
            'user_browse'

        ]
    );
}
    /** show messages sent
     * @Route("/api/messages/sent", name="sent_messages", methods={"GET"})
     */
    public function sentMessagesById(MessagesRepository $messagesRepository, UserRepository $userRepository, $id)
    {
       // find all messages from the user
      $user =  $userRepository->findoneBy([
            'id' => $id
        ]);

        // return json content
        return $this->json200($messagesRepository->findBy(
            [
                'user' => $user->getId()
            ]

        ), [
      "groups" => 'message_browse',
      'user_browse'
     
    ]);
    }

       /** delete message from messagery
    * @param ?Messages $messages
    * @param SerializerInterface $serializerInterface
    * @param EntityManagerInterface $entityManagerInterface
    * @Route("/api/messages/{id<\d+>}/delete", name="delete_message", methods={"PUT"})
    */
    public function deleteAdvertisement(Messages $messages, Request $request, SerializerInterface $serializerInterface, 
    EntityManagerInterface $entityManagerInterface)
    {
        // if errors...
        if ($messages == null) {
            return $this->json404("Pas de messages pour cet identifiant");
        }

        // modify objects
       
      $messages->setIsHidden(true);
    //  $messages->setUpdatedAt(new DateTime());
    
        // update BDD
        $entityManagerInterface->flush();

        // TODO : renvoyer l'information que tout c'est bien passé
        return $this->json(
            $messages,
            Response::HTTP_PARTIAL_CONTENT,
            [
                
            ],
            // put the serialization groups
            [
                "groups" =>
                [
                    "message_browse",
                    "user_browse"
                ]
            ]
        );
    }




}
