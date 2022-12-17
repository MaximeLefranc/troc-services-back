<?php

namespace App\Controller\Api;

use App\Entity\Advertisements;
use App\Repository\AdvertisementsRepository;
use App\Repository\CategoriesRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime as ConstraintsDateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdvertisementsController extends ApiController
{
    /**
     * @Route("/api/advertisements/upload/{id}", name="image_upload_advertisement", methods={"POST"})
     */
    public function uploadImage($id, AdvertisementsRepository $advertisementsRepository, Request $request, EntityManagerInterface $entityManagerInterface, ParameterBagInterface $parameterBag): Response
    {
        $ad = $advertisementsRepository->find($id);
        $image = $request->files->get('file');
        $imageName = uniqid() . '_' . $image->getClientOriginalName();
        $image->move($parameterBag->get('public') . '/img', $imageName);

        $ad->setImageName($imageName);
     
        $entityManagerInterface->persist($ad);
        $entityManagerInterface->flush();

        return $this->json([
            'message' => 'Image uploaded successfully.'
        ],
    Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/advertisements/add", name="add_advertisements", methods={"POST"})
     */
    public function addAdvertisement(

        SerializerInterface $serializerInterface,
        Request $request,
        ValidatorInterface $validatorInterface,
        EntityManagerInterface $em
       
    

   
    ) {

        // retrieve the content by the class Request with the method getContent
        $content = $request->getContent();

        // Verify the JSON and deserialize the JSON content
        try {
            $ad = $serializerInterface->deserialize($content, Advertisements::class, 'json');
        
            $date = new DateTime();
            $date->format('Y-m-d H:i:s');
            $ad->setCreatedAt($date);
            $ad->setUser($this->getUser());

            $ad->setImageName('image-advert.jpeg');
            

            $errors = $validatorInterface->validate($ad);

            if (count($errors) > 0) 
            {
                return $this->json($errors, 400);
            }

           
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => '400',
                'message' => $e->getMessage()
            ], 400);
        }
        $em->persist($ad);
        $em->flush();
        //return the correct http response

        return $this->json([
            'newAdvertId' => $ad->getId()], 
            Response::HTTP_CREATED

    
        );
    }

     /**
     * @Route("/api/advertisements", name="browse_advertisements", methods={"GET"})
     */
    public function browseAdvertisement(AdvertisementsRepository $advertisementsRepository, SerializerInterface $serializerInterface)
    {


        return $this->json200($advertisementsRepository->findAllOrderByCreation(), [
        
            "groups" => 
            'category_browse', // add category to the json content
            'skill_browse',  // add skill to the json content
            'advertisements_browse'
    
          

        ]);
    }

    /**
     * @Route("/api/advertisements/{id<\d+>}", name="read_advertisements", methods={"GET"})
     */
    public function readAdvertisement(AdvertisementsRepository $advertisementsRepository, SerializerInterface $serializerInterface, $id)
    {
        // select all advertisements
        $oneAdvert =  $advertisementsRepository->find($id);
        // if an advert doesn't exist return 404
        if ($oneAdvert == null) {
            return $this->json404(["erreur" => "l'annonce n'a pas été trouvée"]);

            // serialize and return status 200
        }
        return $this->json200(["advertisements" => $oneAdvert], [
            "groups" => 'advertisements_browse',
            'category_browse', // add category to the json content
            'skill_browse',  // add skill to the json content
         
        ]);
    }

    /**
     * @param ?Advertisements $advertisements
     * @param SerializerInterface $serializerInterface
     * @param EntityManagerInterface $entityManagerInterface
     * @Route("/api/advertisements/{id<\d+>}/edit", name="edit_advertisement", methods={"PUT", "PATCH"})
     */
    public function editAdvertisement(
        Advertisements $advertisements,
        Request $request,
        SerializerInterface $serializerInterface,
        EntityManagerInterface $entityManagerInterface
    ) {
        // if errors...
        if ($advertisements == null) {
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
        $advertisements->setUpdatedAt(new DateTime('now'));
        dd($advertisements);
        // update BDD
        $entityManagerInterface->flush();

        // TODO : renvoyer l'information que tout c'est bien passé
        return $this->json(
            $advertisements,
            Response::HTTP_PARTIAL_CONTENT,
            [],
            // put the serialization groups
            [
                "groups" =>
                [
                    "advertisements_browse"
                ]
            ]
        );
    }

    /**
     * @param ?Advertisements $advertisements
     * @param SerializerInterface $serializerInterface
     * @param EntityManagerInterface $entityManagerInterface
     * @Route("/api/advertisements/{id<\d+>}/delete", name="delete_advertisement", methods={"PUT"})
     */
    public function deleteAdvertisement(
        Advertisements $advertisements,
        Request $request,
        SerializerInterface $serializerInterface,
        EntityManagerInterface $entityManagerInterface
    ) {
        // if errors...
        if ($advertisements == null) {
            return $this->json404("Pas d'annonces pour cet identifiant");
        }

        // modify objects

        $advertisements->setIsHidden(true);
        $advertisements->setUpdatedAt(new DateTime('now'));

        // update BDD
        $entityManagerInterface->flush();

        // TODO : renvoyer l'information que tout c'est bien passé
        return $this->json(
            $advertisements,
            Response::HTTP_PARTIAL_CONTENT,
            [],
            // put the serialization groups
            [
                "groups" =>
                [
                    "advertisements_browse"
                ]
            ]
        );
    }
}
