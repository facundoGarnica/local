<?php

namespace App\Repository;

use App\Entity\CalendarioClase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CalendarioClase>
 *
 * @method CalendarioClase|null find($id, $lockMode = null, $lockVersion = null)
 * @method CalendarioClase|null findOneBy(array $criteria, array $orderBy = null)
 * @method CalendarioClase[]    findAll()
 * @method CalendarioClase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarioClaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CalendarioClase::class);
    }

//    /**
//     * @return CalendarioClase[] Returns an array of CalendarioClase objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CalendarioClase
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
