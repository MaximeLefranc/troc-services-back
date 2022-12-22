<?php

namespace App\Controller\Backoffice;

use App\Entity\User;
use App\Form\User1Type;
use App\Form\User1TypeEdit;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/users")
 */
class UsersController extends AbstractController
{


    
    /**
     * @Route("/", name="backoffice_users", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('backoffice/users/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="backoffice_users_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // hash the password
            $passwordHashed = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($passwordHashed);
            $user->setCreated(new \DateTime());

           
        }
            
        return $this->renderForm('backoffice/users/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_users_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('backoffice/users/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backoffice_users_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $newpassword = $form->get('password')->getData();
            // si j'ai des données dans ce champs, j'ai un nouveau mot de passe
            if ($newpassword != null) {
                // le mot de passe est en clair, je le hash
                $passwordHashed = $passwordHasher->hashPassword($user, $newpassword);
                // je met à jour mon entité
                $user->setPassword($passwordHashed);

            }
            
            $user->setUpdated(new DateTime());



            $userRepository->add($user, true);


            return $this->redirectToRoute('backoffice_users', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_users_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('backoffice_users_index', [], Response::HTTP_SEE_OTHER);
    }
}
