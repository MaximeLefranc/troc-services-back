<?php

namespace App\Controller\Api;

use App\Repository\MessagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends ApiController
{
    /**
     * @Route("/api/messages", name="api_messages" methods={"GET"})
     */
    public function browsemessage(MessagesRepository $messagesRepository, $id)
    {
       

        return $this->json200($messagesRepository->findBy([
            'id'=>$id
        ]), [
      "groups" => 'category_browse'
     
    ]);
    }
}
