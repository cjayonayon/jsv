<?php

namespace App\Repository\Admin;

use App\Entity\Admin\Invitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Invitation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invitation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invitation[]    findAll()
 * @method Invitation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invitation::class);
    }

    // /**
    //  * @return Invitation[] Returns an array of Invitation objects
    //  */
    
    public function findMaxInvitedAt($value)
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.userGroup', 'g')
            ->select('Max(i.invitedAt) as max')
            ->andWhere('g.id = :id')
            ->setParameter('id', $value)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function findInvitedAt($value)
    {
        return $this->createQueryBuilder('i')
            ->select('i.invitedAt')
            ->andWhere('i.longId = :id')
            ->setParameter('id', $value)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
