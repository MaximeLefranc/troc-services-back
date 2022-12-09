<?php

Namespace App\Controller\Backoffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
/**
     * @Route("/backoffice/home", name="backoffice_home", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('backoffice/home.html.twig', [
           
        ]);
    }

}