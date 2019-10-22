<?php

namespace App\Repository\EmployeeUser;

use App\Entity\EmployeeUser\Items;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Items|null find($id, $lockMode = null, $lockVersion = null)
 * @method Items|null findOneBy(array $criteria, array $orderBy = null)
 * @method Items[]    findAll()
 * @method Items[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Items::class);
    }

    public function findEmployeeItems($group)
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.employee', 'e')
            ->addSelect('e')
            ->innerJoin('i.itemGroup', 'g')
            ->addSelect('g')
            ->andWhere('i.status = :stat')
            ->setParameter('stat', 'Add')
            ->andWhere('i.itemGroup = :group')
            ->setParameter('group', $group)
            ->orderBy('i.removedAt', 'ASC')
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function checkMaxItemPerHour($id, $date)
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.employee', 'e')
            ->andWhere('e.id = :id')
            ->setParameter('id', $id)
            ->andWhere('i.removedAt >= :date')
            ->setParameter('date', $date)
            ->select('i.removedAt')
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function getItemByEmployee($item,$group)
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.employee', 'e')
            ->addSelect('e')
            ->andWhere('i.itemGroup = :id')
            ->setParameter('id', $group)
            ->andWhere('i.videoId = :item')
            ->setParameter('item', $item)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
