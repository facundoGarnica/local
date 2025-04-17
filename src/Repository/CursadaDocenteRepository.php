<?php

namespace App\Repository;

use App\Entity\CursadaDocente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CursadaDocente>
 *
 * @method CursadaDocente|null find($id, $lockMode = null, $lockVersion = null)
 * @method CursadaDocente|null findOneBy(array $criteria, array $orderBy = null)
 * @method CursadaDocente[]    findAll()
 * @method CursadaDocente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CursadaDocenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CursadaDocente::class);
    }

    public function save(CursadaDocente $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CursadaDocente $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CursadaDocente[] Returns an array of CursadaDocente objects
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

//    public function findOneBySomeField($value): ?CursadaDocente
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
