<?php

namespace App\Denormalizer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DoctrineDenormalizer implements DenormalizerInterface
{
    /**
     * EntityManager
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
    * Constructor
    */
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }

    /**
     * est ce que je sais faire cette denormalisation ?
     *
     * @param mixed $data : ID de l'entité
     * @param string $type : le type de la classe de l'entité (FQCN)
     * @param string|null $format
     */
    public function supportsDenormalization($data, string $type, ?string $format = null): bool
    {

        // exemple : pour Movie::genres
        // dans le json on fournit : "genres" : [id1, id2]
        // dans $data on va recevoir, tour à tour, id1, puis id2 dans un 2eme appel
        // dans $type on va recevoir : App\Entity\Genre

        //? je regarde d'abord si on est en train de désérializer une Entité
        // je regarde si le FQCN commence par App\Entity
        $isEntity = strpos($type, "App\Entity") === 0;

        //? je vérifie que $data est un ID, un entier
        // $data = 617
        $isIdentifiant = is_numeric($data);

        return $isEntity && $isIdentifiant;
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        // TODO : j'ai un identifiant et un FQCN, je fait une recherche dans la BDD : il me faut EntityManager
        // ? on peut pas faire injection de dépendance, vive le constructeur
        $entity = $this->em->find($type, $data);

        return $entity;
    }

}