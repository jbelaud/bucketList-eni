<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wish[]    findAll()
 * @method Wish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }


    public function findCategorizedWishes($categoryId = null)
    {
        //requête en mode chaine DQL
        //on sélectionne les wish ET les catégorie (c)
        //on fait la jointure sur la propriétés de la classe et thats it
        //on pense à préfixer le nom des colonnes par l'alias de la table
        /*
        //version en DQL !
        $dql = "SELECT w, c
                FROM App\Entity\Wish w
                JOIN w.category c
                WHERE w.isPublished = :publishStatus
                ORDER BY w.dateCreated DESC
                ";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter("publishStatus", 1);
        */

        $queryBuilder = $this->createQueryBuilder('w');

        $queryBuilder->addOrderBy('w.dateCreated', 'DESC');

        $queryBuilder
            ->andWhere('w.isPublished = :publishStatus')
            ->setParameter('publishStatus', 1)

            ->join('w.category', 'c')
            ->addSelect('c');


        if($categoryId){
            //$queryBuilder->andWhere();
        }

        //récupère notre objet query, sur lequel on pourra récupérer nos résultats
        $query = $queryBuilder->getQuery();

        //limit
        $query->setMaxResults(200);

        //offset
        $query->setFirstResult(0);

        //récupère tous les résultats
        $wishes = $query->getResult();
        return $wishes;

        //si on a des problèmes de LIMIT avec les jointures...
        //$paginator = new Paginator($query);
        //return $paginator;
    }


    // /**
    //  * @return Wish[] Returns an array of Wish objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Wish
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}