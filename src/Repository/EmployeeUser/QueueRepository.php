<?php

namespace App\Repository\EmployeeUser;

use App\Entity\EmployeeUser\Queue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Queue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Queue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Queue[]    findAll()
 * @method Queue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QueueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Queue::class);
    }

    public function findQueueItems($id,$group)
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.employee', 'e')
            ->addSelect('e')
            ->innerJoin('q.employeeGroup', 'g')
            ->addSelect('g')
            ->innerJoin('q.item', 'i')
            ->addSelect('i')
            ->innerJoin('i.employee', 'ie')
            ->addSelect('ie')
            ->andWhere('q.employeeGroup = :group')
            ->setParameter('group', $group)
            ->andWhere('q.employee = :id')
            ->setParameter('id', $id)
            ->orderBy('i.removedAt', 'ASC')
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
