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
           ->setParameter(':val',new \DateTime()) 
           ->orderBy('s.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
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
