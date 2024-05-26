<?php

namespace App\Repository;

use App\Entity\Establecimiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Establecimiento>
 *
 * @method Establecimiento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Establecimiento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Establecimiento[]    findAll()
 * @method Establecimiento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstablecimientoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Establecimiento::class);
    }


    /**
     * Encuentra establecimientos que coincidan parcialmente con el cueanexo.
     */
    public function findLikeCueanexo(string $cueanexo): array
    {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.cueanexo LIKE :cueanexo')
            ->setParameter('cueanexo', '%' . $cueanexo . '%');

        return $qb->getQuery()->getResult();
    }

    public function add(Establecimiento $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Establecimiento $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Establecimiento[] Returns an array of Establecimiento objects
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

//    public function findOneBySomeField($value): ?Establecimiento
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
