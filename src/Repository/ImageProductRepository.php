<?php

namespace App\Repository;

use App\Entity\ImageProduct;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageProduct[]    findAll()
 * @method ImageProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageProduct::class);
    }

    // /**
    // * @return ImageProduct[] Returns an array of ImageProduct objects
    // */

    // public function findByDisposition(int $productId): array
    // {
    //     return $this->createQueryBuilder('i')
    //         ->where(':product MEMBER OF i.product_id')
    //         ->setParameter('product', $productId)
    //         ->orderBy('i.disposition', 'ASC')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    /*
    public function findOneBySomeField($value): ?ImageProduct
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
