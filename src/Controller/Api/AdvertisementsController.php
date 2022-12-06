<?php

namespace App\Controller\Api;

use App\Repository\AdvertisementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response as BrowserKitResponse;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class AdvertisementsController extends AbstractController
{
    /**
     * @Route("/api/advertisements", name="browse_advertisements", methods={"GET"})
     */
    public function browseAdvertisement(AdvertisementsRepository $advertisementsRepository, SerializerInterface $serializerInterface)
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

    /**
     * @Route("/api/advertisements/{id}", name="read_advertisement", methods={"GET"})
     */
    public function readAdvertisement(AdvertisementsRepository $advertisementsRepository, SerializerInterface $serializerInterface, $id)
    {
        // select all advertisements
      $oneAdvert =  $advertisementsRepository->find($id);
        // if an advert doesn't exist return 404
      if($oneAdvert == null)
      {
        $response = new Response("l'annonce n'a pas été trouvée", 404, [
            "content_type" => "application/json"
           ]);

           return $response;
      }

      else{

      // serialize advertisement, and limit with the groups
      $json = $serializerInterface->serialize($oneAdvert,'json',['groups' => [
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

      /**
     * @Route("/api/advertisements", name="add_advertisement", methods={"POST"})
     */
    public function addAdvertisement(AdvertisementsRepository $advertisementsRepository, SerializerInterface $serializerInterface, HttpFoundationRequest $request)
    {
        // retrieve the content by the class Request with the method getContent
       $newAdvert = $request->getContent();

       // deserialize the JSON content 
       


    }
}
