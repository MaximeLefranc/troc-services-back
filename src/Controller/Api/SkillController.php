<?php

namespace App\Controller\Api;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/skill")
 */
class SkillController extends ApiController
{
    /**
     * @Route("/", name="api_browse_skills", methods={"GET"})
     */
    public function index(SkillRepository $skillRepository): JsonResponse
    {
        return $this->json(
            // les données
            $skillRepository->findAll(),
            // le code HTTP 200
            Response::HTTP_OK,
            // les entètes HTTP, on n'a pas de besoin de les modifier, [] par défaut
            [],
            // c'est ici que je fournis les groupes de serialisation
            [
                "groups" => 
                [
                    "skill_browse"
                ]
            ]
        );
    }

    /**
     * @Route("/{id}", name="api_read_skill", methods={"GET"})
     */
    public function show(Skill $skill = null): Response
    {
        if ($skill === null){

            return $this->json(
                // les données ne sont pas obligatoirement des Entités
                [
                    "erreur" => "compétence introuvable" 
                ],
                // le code HTTP 404
                Response::HTTP_NOT_FOUND,
                // on a pas besoin des autres paramètres
            );
        }

        return $this->json(
            // les données
            $skill,
            // le code HTTP 200
            Response::HTTP_OK,
            // les entètes HTTP, on n'a pas de besoin de les modifier, [] par défaut
            [],
           
            [
                "groups" => 
                [
                    'skill_browse',
                    'category_browse'
                ]
            ]
        );
    }

   
}