<?php

namespace App\Repository;

use App\Entity\Curso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Curso>
 *
 * @method Curso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Curso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Curso[]    findAll()
 * @method Curso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CursoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Curso::class);
    }

    public function save(Curso $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Curso $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    // src/Repository/CursoRepository.php

  public function findByCicloLectivo(string $cicloLectivo): array
{
    return $this->createQueryBuilder('c')
        ->join('c.comision', 'com')
        ->andWhere('com.ciclo_lectivo = :ciclo')
        ->setParameter('ciclo', $cicloLectivo)
        ->getQuery()
        ->getResult();
}



//    /**
//     * @return Curso[] Returns an array of Curso objects
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

//    public function findOneBySomeField($value): ?Curso
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
