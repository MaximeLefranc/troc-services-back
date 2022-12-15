<?php

namespace App\Controller\Backoffice;

use App\Entity\Advertisements;
use App\Form\AdvertisementsType;
use App\Repository\AdvertisementsRepository;
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
        return $this->render('backoffice/advertisement/index.html.twig', [
            'advertisements' => $advertisementsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/moderation", name="moderation_advertisements", methods={"GET"})
     */
    public function moderation(AdvertisementsRepository $advertisementsRepository): Response
    {   
        
        return $this->render('backoffice/advertisement/moderation.html.twig', [
            'advertisements' => $advertisementsRepository->findAll(),
        ]);
    }

     /**
     * @Route("/moderation/{id}", name="read_advertisement_moderation", methods={"GET"})
     */
    public function showModeration(Advertisements $advertisement): Response
    {
        return $this->render('backoffice/advertisement/show_moderation.html.twig', [
            'advertisement' => $advertisement,
        ]);
    }
  


    /**
     * @Route("/add", name="add_advertisement", methods={"GET", "POST"})
     */
    public function add(Request $request, AdvertisementsRepository $advertisementsRepository): Response
    {
        $advertisement = new Advertisements();
        $form = $this->createForm(AdvertisementsType::class, $advertisement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $advertisementsRepository->add($advertisement, true);

            return $this->redirectToRoute('backoffice_advertisements', [], Response::HTTP_SEE_OTHER);
        }

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
        if ($this->isCsrfTokenValid('delete'.$advertisement->getId(), $request->request->get('_token'))) {
            $advertisementsRepository->remove($advertisement, true);
        }

        return $this->redirectToRoute('backoffice_advertisements', [], Response::HTTP_SEE_OTHER);
    }
}
