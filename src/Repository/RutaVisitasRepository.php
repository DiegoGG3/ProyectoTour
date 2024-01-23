<?php

namespace App\Repository;

use App\Entity\RutaVisitas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RutaVisitas>
 *
 * @method RutaVisitas|null find($id, $lockMode = null, $lockVersion = null)
 * @method RutaVisitas|null findOneBy(array $criteria, array $orderBy = null)
 * @method RutaVisitas[]    findAll()
 * @method RutaVisitas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RutaVisitasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RutaVisitas::class);
    }

//    /**
//     * @return RutaVisitas[] Returns an array of RutaVisitas objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RutaVisitas
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
