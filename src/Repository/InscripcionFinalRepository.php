<?php

namespace App\Repository;

use App\Entity\InscripcionFinal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InscripcionFinal>
 *
 * @method InscripcionFinal|null find($id, $lockMode = null, $lockVersion = null)
 * @method InscripcionFinal|null findOneBy(array $criteria, array $orderBy = null)
 * @method InscripcionFinal[]    findAll()
 * @method InscripcionFinal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscripcionFinalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InscripcionFinal::class);
    }

    public function save(InscripcionFinal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InscripcionFinal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return InscripcionFinal[] Returns an array of InscripcionFinal objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InscripcionFinal
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
