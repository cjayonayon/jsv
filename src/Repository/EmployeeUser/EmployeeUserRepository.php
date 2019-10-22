<?php

namespace App\Repository\EmployeeUser;

use App\Entity\EmployeeUser\EmployeeUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EmployeeUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeeUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeeUser[]    findAll()
 * @method EmployeeUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeeUser::class);
    }

    // /**
    //  * @return EmployeeUser[] Returns an array of EmployeeUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmployeeUser
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
