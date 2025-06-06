<?php

namespace App\Repository;

use App\Entity\Asistencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Asistencia>
 *
 * @method Asistencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asistencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asistencia[]    findAll()
 * @method Asistencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AsistenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asistencia::class);
    }

    public function save(Asistencia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Asistencia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
public function findAsistenciasPorCurso(int $cursoId): array
{
    return $this->createQueryBuilder('a')
        ->join('a.cursada', 'cursada')
        ->join('cursada.alumno', 'alumno')
        ->join('a.calendarioClase', 'calendario')
        ->where('cursada.curso = :cursoId')
        ->setParameter('cursoId', $cursoId)
        ->orderBy('alumno.apellido', 'ASC')
        ->addOrderBy('calendario.Fecha', 'ASC')
        ->getQuery()
        ->getResult();
}
//    /**
//     * @return Asistencia[] Returns an array of Asistencia objects
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

//    public function findOneBySomeField($value): ?Asistencia
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
