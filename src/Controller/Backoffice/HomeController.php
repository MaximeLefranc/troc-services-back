<?php

Namespace App\Controller\Backoffice;

use App\Repository\AdvertisementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class HomeController extends AbstractController
{
/**
     * @Route("/backoffice/home", name="backoffice_home", methods={"GET"})
     * @isGRanted("ROLE_ADMIN")
     */
    public function index(AdvertisementsRepository $advertisementsRepository): Response
    {

        return $this->render('backoffice/home.html.twig', [
            'advertisement' => $advertisementsRepository->findAdvertToModerate()
        ]);
    }

}