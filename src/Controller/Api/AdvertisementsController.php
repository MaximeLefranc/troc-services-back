<?php

namespace App\Controller\Api;

use App\Entity\Advertisements;
use App\Repository\AdvertisementsRepository;
use DateTime;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdvertisementsController extends ApiController
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
            'skill_read'
        ]]);

        //send response

        $response = new Response($json, 200, [
            "content_type" => "application/json"
        ]);
        return $response;
    }

    

    /**
     * @Route("/api/advertisements", name="add_advertisement", methods={"POST"})
     */
    public function addAdvertisement(
    
    SerializerInterface $serializerInterface, 
    Request $request, 
    ValidatorInterface $validatorInterface,
    EntityManagerInterface $em)
    {
       
        // retrieve the content by the class Request with the method getContent
        $content = $request->getContent();

        // Verify the JSON and deserialize the JSON content
     try{
        $ad = $serializerInterface->deserialize($content, Advertisements::class, 'json');
        $ad->setCreatedAt(new \DateTime());

        $errors = $validatorInterface->validate($ad);

        if(count($errors)> 0){
            return $this->json($errors, 400);
        }

        $em->persist($ad);
        $em->flush();
     } catch (NotEncodableValueException $e)
     {
        return $this->json([
            'status' => '400',
            'message' => $e->getMessage()
        ],400);
     }
        
        //return the correct http response

        return $this->json(
            $ad,
            Response::HTTP_CREATED,

            [],
            [
                // list of groups to use
                "groups" => 'get_advertisements_collection'

            ]
        );
    }

    /**
     * @Route("/api/advertisements/{id<\d+>}", name="read_advertisement", methods={"GET"})
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
                'skill_read'
            ]]);

            //send response

            $response = new Response($json, 200, [
                "content_type" => "application/json"
            ]);
            return $response;
        }
    }

    /**
    * @param ?Advertisements $advertisements
    * @param SerializerInterface $serializerInterface
    * @param EntityManagerInterface $entityManagerInterface
    * @Route("/api/advertisements/{id<\d+>}", name="edit_advertisement", methods={"PUT", "PATCH"})
    */
    public function editAdvertisement(Advertisements $advertisements, Request $request, SerializerInterface $serializerInterface, 
    EntityManagerInterface $entityManagerInterface)
    {
        // if errors...
        if ($advertisements === null) {
            return $this->json404("Pas d'annonces pour cet identifiant");
        }

        // receive object
        $jsonContent = $request->getContent();

        // deserialize the object
        $serializerInterface->deserialize(
            $jsonContent,
            Advertisements::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $advertisements]
        );

        // update BDD
        $entityManagerInterface->flush();

        // TODO : renvoyer l'information que tout c'est bien passé
        return $this->json(
            $advertisements,
            Response::HTTP_PARTIAL_CONTENT,
            [
                // redirection
                "Location" => $this->generateUrl("read_advertisement", ["id" => $advertisements->getId()])
            ],
            // put the serialization groups
            [
                "groups" =>
                [
                    "advertisements_read"
                ]
            ]
        );
    }
}
