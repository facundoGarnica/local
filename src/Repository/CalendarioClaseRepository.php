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

   public function findByFechaAndCurso(\DateTimeInterface $fecha, int $cursoId): ?CalendarioClase
{
    $fechaInicio = (clone $fecha)->setTime(0, 0, 0);
    $fechaFin = (clone $fechaInicio)->modify('+1 day');

    $qb = $this->createQueryBuilder('c')
        ->andWhere('c.Curso = :cursoId')
        ->andWhere('c.Fecha >= :fechaInicio')
        ->andWhere('c.Fecha < :fechaFin')
        ->setParameter('cursoId', $cursoId)
        ->setParameter('fechaInicio', $fechaInicio)
        ->setParameter('fechaFin', $fechaFin)
        ->setMaxResults(1);

    $resultado = $qb->getQuery()->getOneOrNullResult();

    if ($resultado) {
        error_log('Fecha en base encontrada: ' . $resultado->getFecha()->format('Y-m-d H:i:s'));
    } else {
        error_log('No se encontró calendario para fecha entre ' . $fechaInicio->format('Y-m-d H:i:s') . ' y ' . $fechaFin->format('Y-m-d H:i:s'));
    }

    return $resultado;
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
