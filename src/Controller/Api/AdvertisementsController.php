<?php

namespace App\Controller\Api;

use App\Repository\AdvertisementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response as BrowserKitResponse;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class AdvertisementsController extends AbstractController
{
    /**
     * @Route("/api/advertisements", name="api_advertisements", methods={"GET"})
     */
    public function index(AdvertisementsRepository $advertisementsRepository, SerializerInterface $serializerInterface)
    {
        // select all advertisements
      $allAd =  $advertisementsRepository->findAll();

        // serialize advertisement, and limit with the groups
        $json = $serializerInterface->serialize($allAd,'json',['groups' => [
            'get_advertisements_collection',
            'category_read',
            'skill_read']] );

            //send response

           $response = new Response($json, 200, [
            "content_type" => "application/json"
           ]);
        return $response;
    }
}
