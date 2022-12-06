<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user")
 */

class UserController extends AbstractController
{
    /**
     * @Route("/", name="api_browse_users", methods={"GET"})
     */
    public function index(UserRepository $userRepository): JsonResponse
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
                    "user_browse"
                ]
            ]
        );
    }

    /**
     * @Route("/create", name="api_add_user", methods={"GET", "POST"})
     */
    public function create(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_a_p_i_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('api/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="api_read_user", methods={"GET"})
     */
    public function read(User $user = null): JsonResponse // POUR CETTE ROUTE, ON DOIT ENVOYER UN ID??
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
                    "user_browse"
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

        return $this->redirectToRoute('app_a_p_i_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
