<?php

namespace App\Repository;

use App\Entity\Sysadmin\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    /**
     * 
     */
    
    public function findAllEmployeesByLastnameAsc()
    {
        return $this->createQueryBuilder('e')
            ->select("
                CONCAT(e.lastName, ', ',e.firstName, ' ',e.middleName) as fullname, 
                e.birthDate, 
                e.address, 
                e.telNumber, 
                e.gender, 
                e.dateEmployed, 
                e.salary,
                e.id,
                e.employeeId")
            ->orderBy('e.lastName', 'ASC')
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function findEmployeePayrolls($id)
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.employeePayrolls', 'a')
            ->addSelect('a')
            ->andWhere('e.employeeId = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findEmployeeCount($id)
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.employeeGroup', 'g')
            ->select('COUNT(e.id)')
            ->andWhere('g.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }


    public function findEmployeesByGroup($id)
    {
        return $this->createQueryBuilder('e')
            ->where('e.employeeGroup = :id')
            ->setParameter('id', $id)
            ->orderBy('e.lastName', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

}
