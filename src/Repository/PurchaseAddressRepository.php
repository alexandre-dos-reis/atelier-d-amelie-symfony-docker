<?php

namespace App\Repository;

use App\Entity\PurchaseAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PurchaseAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurchaseAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurchaseAddress[]    findAll()
 * @method PurchaseAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurchaseAddress::class);
    }

    // /**
    //  * @return PurchaseAddress[] Returns an array of PurchaseAddress objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PurchaseAddress
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
