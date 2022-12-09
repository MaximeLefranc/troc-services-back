<?php

namespace App\Controller\Backoffice;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/categories")
 */
class CategoriesController extends AbstractController
{
    /**
     * @Route("/", name="backoffice_categories", methods={"GET"})
     */
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('backoffice/categories/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="backoffice_categories_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategoriesRepository $categoriesRepository): Response
    {
        $category = new Categories();
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category->setCreatedAt(new DateTime());
            $categoriesRepository->add($category, true);

            return $this->redirectToRoute('backoffice_categories', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/categories/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_categories_show", methods={"GET"})
     */
    public function show(Categories $category): Response
    {
        return $this->render('backoffice/categories/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backoffice_categories_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categories $category, CategoriesRepository $categoriesRepository): Response
    {
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriesRepository->add($category, true);

            return $this->redirectToRoute('backoffice_categories', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/categories/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_categories_delete", methods={"POST"})
     */
    public function delete(Request $request, Categories $category, CategoriesRepository $categoriesRepository): Response
    {
    if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
        $categoriesRepository->remove($category, true);
    }

    return $this->redirectToRoute('backoffice_categories', [], Response::HTTP_SEE_OTHER);
}
}
