<?php

namespace App\Controller\Backoffice;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/skills")
 */
class SkillController extends AbstractController
{
    /**
     * @Route("/", name="backoffice_skills", methods={"GET"})
     */
    public function index(SkillRepository $skillRepository): Response
    {
        return $this->render('backoffice/skill/index.html.twig', [
            'skills' => $skillRepository->findAll(),
        ]);
    }

    /**
     * @Route("/add", name="add_skill", methods={"GET", "POST"})
     */
    public function add(Request $request, SkillRepository $skillRepository): Response
    {
        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skillRepository->add($skill, true);

            return $this->redirectToRoute('backoffice_skills', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/skill/new.html.twig', [
            'skill' => $skill,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="read_skill", methods={"GET"})
     */
    public function show(Skill $skill): Response
    {
        return $this->render('backoffice/skill/show.html.twig', [
            'skill' => $skill,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit_skill", methods={"GET", "POST"})
     */
    public function edit(Request $request, Skill $skill, SkillRepository $skillRepository): Response
    {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skillRepository->add($skill, true);

            return $this->redirectToRoute('backoffice_skills', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/skill/edit.html.twig', [
            'skill' => $skill,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete_skill", methods={"POST"})
     */
    public function delete(Request $request, Skill $skill, SkillRepository $skillRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$skill->getId(), $request->request->get('_token'))) {
            $skillRepository->remove($skill, true);
        }

        return $this->redirectToRoute('backoffice_skills', [], Response::HTTP_SEE_OTHER);
    }
}
