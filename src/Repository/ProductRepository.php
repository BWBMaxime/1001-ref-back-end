<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    //  /**
    //   * @return Product[] Returns an array of Product objects
    //   */
    
    // public function getMinimalProd()
    // {
    //     return $this->createQueryBuilder('products')
    //         // ->select('DISTINCT p.id', 'p.name', 'p.category', 'p.description', 'p.photo')
    //         // ->from('App\Entity\Product', 'p')
    //         // ->addSelect('p.tags')
    //         // ->from('App\Entity\Tags', 'tags')
    //         ->from('App\Entity\Product', 'p')
    //         ->from('App\Entity\Tags', 't')
    //         ->join('p.tags', 't.name')
    //         ->select('DISTINCT p.id', 'p.name', 'p.description', 'p.category', 'p.photo', 'p.tags')
    //         ->getQuery()
    //         ->getResult();
    // }
    

    /*
    public function findOneBySomeField($value): ?Product
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
