<?php

namespace App\Repository;

use App\Entity\ExamenAlumno;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExamenAlumno>
 *
 * @method ExamenAlumno|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamenAlumno|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamenAlumno[]    findAll()
 * @method ExamenAlumno[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamenAlumnoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExamenAlumno::class);
    }

    public function save(ExamenAlumno $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ExamenAlumno $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ExamenAlumno[] Returns an array of ExamenAlumno objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ExamenAlumno
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
