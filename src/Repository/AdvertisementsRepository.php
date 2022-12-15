<?php

namespace App\Repository;

use App\Entity\Advertisements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Advertisements>
 *
 * @method Advertisements|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advertisements|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advertisements[]    findAll()
 * @method Advertisements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertisementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advertisements::class);
    }

    public function add(Advertisements $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Advertisements $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

        
    public function findAllOrderByCreation(): array
    {
        //1. donner l'alias de l'objet
        return $this->createQueryBuilder('a')
            // trier par duration
            ->orderBy("a.createdAt", "DESC")
            // limiter le nombre de résultats
            ->setMaxResults(10)
            // on récupère la requete, puis les résultats
            ->getQuery()
            ->getResult();
    }

    public function findAdvertToModerate()
    {
  
       return $this->createQueryBuilder('a')
            ->andWhere('a.approved = false')
            ->andWhere('a.isHidden = false')
        

            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Advertisements[] Returns an array of Advertisements objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Advertisements
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
