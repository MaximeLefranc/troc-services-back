<?php


namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Mailer;

class ContactController extends AbstractController
{
    /** 
     * @Route("/api/contact", name="contact", methods={"GET", "POST"})
     */
    public function contact(Request $request, MailerInterface $mailer, SerializerInterface $serializerInterface)
    {
        // on récupère les infos reçue en json
        $jsonContent = $request->getContent();
      

        $contact = json_decode($jsonContent, true); // on transforme les données json en tableau php
       

        // on insère les valeurs des clés du tableau créé dans des variables
        $fullName = $contact['fullName'];
        $userEmail = $contact['userEmail'];
        $subject = $contact['subject'];
        $message = $contact['message'];

        
        $transport = Transport::fromDsn('smtp://trocservicesymfony@gmail.com:dvecqbbtdoyejwwv@smtp.gmail.com:587'); // on créé une variable transport pour l'envoi de mail via SMTP


        $mailer = new Mailer($transport); // on créé une instance de Mailer avec notre dsn en paramètre

        $email = (new Email()) //création du mail de contact (from, to, sujet, contenu du message)
    
        ->to('trocservicesymfony@gmail.com')
       
        ->from($userEmail)
        ->subject("New Message from $fullName : $subject")
        ->text($message);
        $mailer->send($email); //envoi du mail créé ci-dessus en utilisant l'objet Mailer
       
        return $this->json('Message envoyé');
    }
}