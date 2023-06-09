<?php

namespace App\Controller\Api;

use App\Entity\Image;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\AdvertisementsRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * @Route("/api/user")
 */


class UserController extends ApiController
{
    /**
     * @Route("/upload/{id}", name="image_upload", methods={"POST", "PUT"})
     */
    public function uploadImage($id, UserRepository $userRepository, Request $request, EntityManagerInterface $entityManagerInterface, ParameterBagInterface $parameterBag): Response
    {

        $user = $userRepository->find($id);
        $image = $request->files->get('file');
        $imageName = uniqid() . '_' . $image->getClientOriginalName();
        $image->move($parameterBag->get('public') . '/img', $imageName);

        $user->setImageName($imageName);


        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();

        return $this->json([
            'message' => 'Image uploaded successfully.'
        ]);
    }

    /**
     * @Route("/new", name="api_create_user", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializerInterface
     * @param ValidatorInterface $validatorInterface
     * @param EntityManagerInterface $entityManagerInterface
     * @param UserPasswordHasherInterface $passwordHasher
     */

    public function new(UserRepository $userRepository, ValidatorInterface $validatorInterface, Request $request, SerializerInterface $serializerInterface, EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $passwordHasher)
    {

        $jsonContent = $request->getContent();


        try {
            $newUser = $serializerInterface->deserialize($jsonContent, User::class, 'json');


            $newUser->setUsername($newUser->getEmail()); //string hashPassword(PasswordAuthenticatedUserInterface $user, string $plainPassword)    Hashes the plain password for the given user.
            $hashedPassword = $passwordHasher->hashPassword(
                $newUser,
                $newUser->getPassword()
            );
            $newUser->setPassword($hashedPassword)
                ->setReference($this->referenceFormat())
                ->setRoles(["ROLE_USER"])
                ->setCreated(new DateTime());

            if ($newUser->setImageFile() === null) {
                $newUser->setImageName('photo-avatar.jpeg');
            }

            $errors = $validatorInterface->validate($newUser);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
        } catch (Exception $e) {
            return $this->json(
                "JSON mal formé",
                Response::HTTP_BAD_REQUEST
            );
        }

        $email = $newUser->getEmail();
        $nickname = $newUser->getNickname();
        $checkEmail = $userRepository->findByEmail($email);

        if ($checkEmail) {

            return $this->json('Cette adresse email est déja associée à un compte', 400);
        }

        $checkNickname = $userRepository->findByNickname($nickname);

        if ($checkNickname) {

            return $this->json('Ce pseudo est déja utilisé par un utilisateur', 400);
        }


        $entityManagerInterface->persist($newUser);
        $entityManagerInterface->flush();

        return $this->json(
            [
                'newUserId' => $newUser->getId()
            ],
            Response::HTTP_CREATED
        );
    }





    public function referenceFormat()
    {
        return 'REP' . substr(date('Y'), 2) . date('md') . uniqid();
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
                    'user_read',
                    'skill_browse',
                    'category_browse'

                ]
            ]
        );
    }



    /**
     * @Route("/{id}", name="api_read_user", methods={"GET"})
     */
    public function read(User $user = null): JsonResponse // POUR CETTE ROUTE, ON DOIT ENVOYER UN ID?? oui
    {

        if ($user === null) {

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
            // c'est ici que je fournis les groupes de serialisations
            [
                "groups" =>
                [
                    // AJouter les advertissement pour afficher les annonces pour un profils utilisateur
                    'user_read', // AJouter les advertissement pour afficher les annonces pour un profils utilisateur
                    'skill_browse'


                ]
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="api_edit_user", methods={"GET", "POST", "PUT"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function edit(User $user, UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validatorInterface, SerializerInterface $serializerInterface, Request $request, EntityManagerInterface $em)
    {
        if ($user === null) {
            return $this->json404("Pas de membre pour cet ID");
        }

        $content = $request->getContent();

        $serializerInterface->deserialize(
            $content,
            User::class,
            'json',
            //? avec le paramètre context, on précise l'objet à mettre à jour 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
        ); // on met à jour la BDD
        $user->setUpdated(new DateTime('now'));
        $em->flush();

        // TODO : renvoyer l'information que tout c'est bien passé
        return $this->json(
            $user,
            Response::HTTP_PARTIAL_CONTENT,
            [
                // proposition de redirection
                "Location" => $this->generateUrl("api_read_user", ["id" => $user->getId()])
            ],
            // c'est ici que je fournis les groupes de serialisation
            [
                // list of groups to use
                "groups" => ['user_read']
            ]

        );
    }


    /**
     * @Route("/{id}/delete", name="delete_user", methods={"DELETE"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function delete(Request $request, User $user, UserRepository $userRepository, TokenStorageInterface $tokenStorage): Response
    {


        $userRepository->remove($user, true);


        return $this->json($user, Response::HTTP_ACCEPTED, [], [
            // list of groups to use
            "groups" => [
                'user_read',
                'skill_browse',

                'message_browse'
            ]

        ]);
    }
}
