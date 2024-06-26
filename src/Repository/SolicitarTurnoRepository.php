<?php

namespace App\Repository;

use App\Entity\SolicitarTurno;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SolicitarTurno>
 *
 * @method SolicitarTurno|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitarTurno|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitarTurno[]    findAll()
 * @method SolicitarTurno[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitarTurnoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SolicitarTurno::class);
    }

    /**
    * @return SolicitarTurno[] Returns an array of SolicitarTurno objects
    */
    public function findByMesPosterior(): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.fecha >= :val')
            ->andWhere('s.estado <> 3')
            ->setParameter(':val',new \DateTime()) 
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    //vamos a buscar todos los registros que coincidan con la fecha seleccionada y el turno seleccionado
    //y el estado si encontrara ese registro sea distinto de rechazado.
    /**
    * @return SolicitarTurno[] Returns an array of SolicitarTurno objects
    */
    public function findFechaYturno( $turnoSolicitado,$fechaSolicitadaString)
    {
        return $this->createQueryBuilder('st')
        ->andWhere('st.fecha = :fecha')
        ->andWhere('st.turno = :turno')
        ->andWhere('st.estado <> 3')

        ->setParameter('fecha', $fechaSolicitadaString)
        ->setParameter('turno', $turnoSolicitado)
        ->getQuery()
        ->getOneOrNullResult();
        ;
    }

    //vamos a buscar un registro que coincida con que la fecha ingresada este entre un intervalo de 30 dias
    // y que el solicitante sea el  mismo que esta en el registro

    /**
     * @return SolicitarTurno[] Returns an array of SolicitarTurno objects
     */
    public function findbySolicitante($solicitante, $fechaSolicitada)
    {
        // Clonar la fecha para evitar modificar el objeto original
        $fechaInicioIntervalo = (clone $fechaSolicitada)->modify('-30 days')->format('Y-m-d');
        $fechaFinIntervalo = (clone $fechaSolicitada)->modify('+30 days')->format('Y-m-d');

        // dump( $fechaInicioIntervalo);
        // dd($fechaFinIntervalo);
        
        return $this->createQueryBuilder('st')
            ->where('st.fecha BETWEEN :fechaInicio AND :fechaFin')
            ->andWhere('st.solicitante = :solicitante')
            ->setParameter('fechaInicio', $fechaInicioIntervalo)
            ->setParameter('fechaFin', $fechaFinIntervalo)
            ->setParameter('solicitante', $solicitante)
            ->getQuery()
            ->getResult();
    }

  
    public function add(SolicitarTurno $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SolicitarTurno $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SolicitarTurno[] Returns an array of SolicitarTurno objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SolicitarTurno
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
