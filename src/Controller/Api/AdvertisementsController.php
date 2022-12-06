<?php

namespace App\Controller\Api;

use App\Entity\Advertisements;
use App\Repository\AdvertisementsRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response as BrowserKitResponse;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        $json = $serializerInterface->serialize($allAd, 'json', ['groups' => [
            'get_advertisements_collection',
            'category_read',
            'skill_read']]);

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
        if ($oneAdvert == null) {
            $response = new Response("l'annonce n'a pas été trouvée", 404, [
                "content_type" => "application/json"
               ]);

            return $response;
        } else {
            // serialize advertisement, and limit with the groups
            $json = $serializerInterface->serialize($oneAdvert, 'json', ['groups' => [
              'get_advertisements_collection',
              'category_read',
              'skill_read']]);

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
    public function addAdvertisement(AdvertisementsRepository $advertisementsRepository, SerializerInterface $serializerInterface, HttpFoundationRequest $request, ValidatorInterface $validatorInterface)
    {
        // retrieve the content by the class Request with the method getContent
        $content = $request->getContent();

        // Verify the JSON and deserialize the JSON content
        try {
            $newAdvert = $serializerInterface->deserialize($content, Advertisements::class, 'json');
        } catch(Exception $e) { // if the json is not ok
            // return error
            return $this->json("Le contenu du JSON est mal formé", Response::HTTP_BAD_REQUEST);
        }
        $errors = $validatorInterface->validate($newAdvert);

        
            //add the new advertisement in BDD, persist + flush
            $advertisementsRepository->add($newAdvert, true);

            //return the correct http response

            return $this->json(
                $newAdvert,
                Response::HTTP_CREATED,
                [
                    // Redirection
                    "Location"=> $this->generateUrl('read_advertisement', ["id" => $newAdvert->getId()])
                ],
                [
                    // list of groups to use
                    "groups" => 'get_advertisements_collection'
                 
                    ]
            );
        }
       
    }

