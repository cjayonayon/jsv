<?php

namespace App\Repository\EmployeeUser;

use App\Entity\EmployeeUser\EmployeeInvitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EmployeeInvitaion|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeeInvitaion|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeeInvitaion[]    findAll()
 * @method EmployeeInvitaion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeInvitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeeInvitation::class);
    }

    // /**
    //  * @return EmployeeInvitaion[] Returns an array of EmployeeInvitaion objects
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
    public function findOneBySomeField($value): ?EmployeeInvitaion
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
