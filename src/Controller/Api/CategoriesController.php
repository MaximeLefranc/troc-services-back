<?php

namespace App\Controller\Api;

use App\Repository\CategoriesRepository;

use Symfony\Component\Routing\Annotation\Route;


class CategoriesController extends ApiController
{
    /**
     * @Route("/api/categories", name="browse_categories")
     */
    public function browseCategories(CategoriesRepository $categoriesRepository)
    {
       

        return $this->json200($categoriesRepository->findAll(), [
      "groups" => 'category_browse'
     
    ]);
    }

     /**
     * @Route("/api/categories/{id<\d+>}", name="read_categories", methods={"GET"})
     */
    public function readAdvertisement(CategoriesRepository $categoryRepository, $id)
    {
        // select one category
        $onecategory =  $categoryRepository->find($id);
        // if an advert doesn't exist return 404
        if ($onecategory == null) {
           return $this->json404(["erreur" => "la catégorie n'a pas été trouvée"]);

            // serialize and return status 200
        } return $this->json200([ "category" => $onecategory],[
            "groups" => 
              'category_browse'
             
          ]);

        
    }

}
