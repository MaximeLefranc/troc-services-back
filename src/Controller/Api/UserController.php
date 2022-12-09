<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTime;

/**
 * @Route("/api/user")
 */

class UserController extends AbstractController
{


    /**
     * @Route("/register", name="api_create_user", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializerInterface
     * @param ValidatorInterface $validatorInterface
     * @param EntityManagerInterface $entityManagerInterface
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function addUser(UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validatorInterface, SerializerInterface $serializerInterface, Request $request, EntityManagerInterface $em)
    {
        

        $content = $request->getContent();

        

        try{
        $user = $serializerInterface->deserialize($content, User::class, 'json');
        
        $user->setUsername($user->getEmail()); //string hashPassword(PasswordAuthenticatedUserInterface $user, string $plainPassword)    Hashes the plain password for the given user.
        $hashedPassword = $passwordHasher->hashPassword(
            $user,$user->getPassword()
        );
        $user->setPassword($hashedPassword);
        $user->setReference($this->referenceFormat());
        $user->setCreated(new \DateTime());
        $user->setUpdated(new \DateTime());

        $errors = $validatorInterface->validate($user);

        if(count($errors)> 0){
            return $this->json($errors, 400);
        }

        $em->persist($user);
        $em->flush();
        } 
        catch (NotEncodableValueException $e)
        {
       return $this->json([
           'status' => '400',
           'message' => $e->getMessage()
       ],400);}

        return $this->json(
        Response::HTTP_CREATED,
        $user,
        [
            // list of groups to use
            "groups" => 'user_browse'

        ]);
    }


    public function referenceFormat()
    {
        return 'REP'.substr(date('Y'), 2).date('md').uniqid();
    }


    /**
     * @Route("/", name="api_browse_users", methods={"GET"})
     * 
     */
    public function browse(UserRepository $userRepository): JsonResponse
    {
        return $this->json(
            // les données
            $userRepository->findAll(),
            // le code HTTP 200
            Response::HTTP_OK,
            // les entètes HTTP, on n'a pas de besoin de les modifier, [] par défaut
            [],
            // c'est ici que je fournis les groupes de serialisation
            [
                "groups" => 
                [
                    "user_browse",
                    'skill_browse' // AJouter les advertissement pour afficher les annonces pour un profils utilisateur
                ]
            ]
        );
    }



    /**
     * @Route("/{id}", name="api_read_user", methods={"GET"})
     */
    public function read(User $user = null): JsonResponse // POUR CETTE ROUTE, ON DOIT ENVOYER UN ID?? oui
    {
        if ($user === null){

            return $this->json(
                // les données ne sont pas obligatoirement des Entités
                [
                    "erreur" => "utilisateur introuvable" 
                ],
                // le code HTTP 404
                Response::HTTP_NOT_FOUND,
                // on a pas besoin des autres paramètres
            );
        }

        return $this->json(
            // les données
            $user,
            // le code HTTP 200
            Response::HTTP_OK,
            // les entètes HTTP, on n'a pas de besoin de les modifier, [] par défaut
            [],
            // c'est ici que je fournis les groupes de serialisation
            [
                "groups" => 
                [
                    "user_browse", // AJouter les advertissement pour afficher les annonces pour un profils utilisateur
                    'skill_browse' // préviser a nicolas que c'est skill browse et non skill read pour les advertisements
                ]
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="api_edit_user", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_a_p_i_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('api/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete_user", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('api_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
