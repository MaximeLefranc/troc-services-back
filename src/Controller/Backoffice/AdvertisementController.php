<?php

namespace App\Controller\Backoffice;

use App\Entity\Advertisements;
use App\Form\AdvertisementsType;
use App\Repository\AdvertisementsRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/advertisement")
 */
class AdvertisementController extends AbstractController
{
    /**
     * @Route("/", name="backoffice_advertisements", methods={"GET"})
     */
    public function index(AdvertisementsRepository $advertisementsRepository): Response
    {
        // return the twig view and the repository to show all repositories
        return $this->render('backoffice/advertisement/index.html.twig', [
            'advertisements' => $advertisementsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/moderation", name="moderation_advertisements", methods={"GET", "PUT"})
     */
    public function moderation(AdvertisementsRepository $advertisementsRepository): Response
    {
        // return the twig view and the repository to show advert who make moderations
        return $this->render('backoffice/advertisement/moderation.html.twig', [
            'advertisements' => $advertisementsRepository->findAdvertToModerate(),
        ]);
    }

    /**
     * @Route("/moderation/{id}", name="read_advertisement_moderation", methods={"GET"})
     */
    public function showModeration(Advertisements $advertisement, AdvertisementsRepository $advertisementsRepository): Response
    {

        // return the twig view and the repository to show one advert to moderate
        return $this->render('backoffice/advertisement/show_moderation.html.twig', [
            'advertisement' => $advertisement,
        ]);
    }


    /**
     * @Route("/moderation/{id}/approve", name="approve_advertisement", methods={"GET","PUT"})
     */
    public function approveAdvertisement(Advertisements $advertisement, AdvertisementsRepository $advertisementsRepository): Response
    {
        //put the set approve to true
        $approveAdvert =  $advertisement->setApproved(true)
            ->setUpdatedAt(new DateTime);
        // persist + flush
        $advertisementsRepository->add($approveAdvert, true);
        $this->addFlash("advertisement-approve", "L'annonce à bien été mise en ligne");
        // redirect route
        return $this->redirectToRoute('moderation_advertisements');


        return $this->render('backoffice/advertisement/show_moderation.html.twig', [
            'advertisement' => $advertisement,
        ]);
    }


    /**
     * @Route("/moderation/{id}/refuse", name="refuse_advertisement", methods={"GET","PUT"})
     */
    public function refuseAdvertisement(Advertisements $advertisement, AdvertisementsRepository $advertisementsRepository): Response
    {
        //put the set approve to true
        $refuseAdvert =  $advertisement->setApproved(false)
            ->setIsHidden(true)
            ->setUpdatedAt(new DateTime);
        // persist + flush
        $advertisementsRepository->add($refuseAdvert, true);
        // add a flash message 
        $this->addFlash("advertisement-refuse", "L'annonce à bien été refusée");
        // redirect route
        return $this->redirectToRoute('moderation_advertisements');

        return $this->render('backoffice/advertisement/show_moderation.html.twig', [
            'advertisement' => $advertisement,
        ]);
    }


    /**
     * @Route("/add", name="add_advertisement", methods={"GET", "POST"})
     */
    public function add(Request $request, AdvertisementsRepository $advertisementsRepository): Response
    {
        // make a new entry
        $advertisement = new Advertisements();
        // create the form
        $form = $this->createForm(AdvertisementsType::class, $advertisement);

        $form->handleRequest($request);
        // if the form is valid and submit
        if ($form->isSubmitted() && $form->isValid()) {
            // persist + flush
            $advertisementsRepository->add($advertisement, true);
            // redirection after the add
            return $this->redirectToRoute('backoffice_advertisements', [], Response::HTTP_SEE_OTHER);
        }
        // return the wiew to show and the form
        return $this->renderForm('backoffice/advertisement/new.html.twig', [
            'advertisement' => $advertisement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="read_advertisement", methods={"GET"})
     */
    public function show(Advertisements $advertisement): Response
    {
        return $this->render('backoffice/advertisement/show.html.twig', [
            'advertisement' => $advertisement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit_advertisement", methods={"GET", "POST"})
     */
    public function edit(Request $request, Advertisements $advertisement, AdvertisementsRepository $advertisementsRepository): Response
    {
        $form = $this->createForm(AdvertisementsType::class, $advertisement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $advertisementsRepository->add($advertisement, true);

            return $this->redirectToRoute('backoffice_advertisements', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/advertisement/edit.html.twig', [
            'advertisement' => $advertisement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete_advertisement", methods={"POST"})
     */
    public function delete(Request $request, Advertisements $advertisement, AdvertisementsRepository $advertisementsRepository): Response
    {
        // compare the CSRF token
        if ($this->isCsrfTokenValid('delete' . $advertisement->getId(), $request->request->get('_token'))) {
            // remove the advert (persist + flush)
            $advertisementsRepository->remove($advertisement, true);
        }

        return $this->redirectToRoute('backoffice_advertisements', [], Response::HTTP_SEE_OTHER);
    }
}
