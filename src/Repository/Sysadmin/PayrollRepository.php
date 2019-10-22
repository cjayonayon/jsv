<?php

namespace App\Repository\Sysadmin;

use App\Entity\Sysadmin\Payroll;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Payroll|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payroll|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payroll[]    findAll()
 * @method Payroll[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayrollRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payroll::class);
    }

    // /**
    //  * @return Payroll[] Returns an array of Payroll objects
    //  */
    
    // SELECT SUM(amount) as total , employee_payroll_id, group_payroll_id FROM `payroll` WHERE group_payroll_id = 1 group BY employee_payroll_id
    public function sumOfAmountForPayroll($value)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.groupPayroll', 'g')
            ->innerJoin('p.employeePayroll', 'e')
            ->andWhere('p.groupPayroll = :val')
            ->setParameter('val', $value)
            ->select('SUM(p.amount) as total, e.employeeId, e.firstName, e.middleName, e.lastName,g.groupName, g.id as groupId')
            ->groupBy('p.employeePayroll')
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function findEmployeePayrolls($id)
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.employeePayroll', 'a')
            ->addSelect('a')
            ->andWhere('a.employeeId = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }
    
    // SELECT EXTRACT(month from payment_date) as mon, SUM(amount) FROM `payroll` WHERE employee_payroll_id = 1 GROUP BY mon
    public function findMonthlyPayrolls($id)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.employeePayroll', 'e')
            ->innerJoin('e.employeeGroup', 'g')
            ->andWhere('e.employeeId = :id')
            ->setParameter('id', $id)
            ->select("
                CONCAT(e.firstName, ' ',e.middleName, ' ', e.lastName) as fullname,
                g.groupName, MONTH(p.paymentDate) as mon, 
                YEAR(p.paymentDate) as yr, 
                SUM(p.amount) as total")
            ->groupBy('mon, yr')
            ->orderBy('yr, mon', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // SELECT EXTRACT(year from payment_date) as year, SUM(amount) FROM `payroll` WHERE employee_payroll_id = 1 GROUP BY year
    public function findYearlyPayrolls($id)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.employeePayroll', 'e')
            ->innerJoin('e.employeeGroup', 'g')
            ->andWhere('e.employeeId = :id')
            ->setParameter('id', $id)
            ->select('g.groupName, YEAR(p.paymentDate) as yr, SUM(p.amount) as total')
            ->groupBy('yr')
            ->orderBy('yr', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findMonthlyGroupPayrolls($id)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.employeePayroll', 'e')
            ->innerJoin('e.employeeGroup', 'g')
            ->andWhere('g.id = :id')
            ->setParameter('id', $id)
            ->select("
                CONCAT(e.lastName, ', ',e.firstName, ' ',e.middleName) as fullname,
                g.groupName, 
                MONTH(p.paymentDate) as mon, 
                YEAR(p.paymentDate) as yr, 
                SUM(p.amount) as total")
            ->groupBy('fullname, mon, yr')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findMonthlyGroupTotal($id)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.employeePayroll', 'e')
            ->innerJoin('e.employeeGroup', 'g')
            ->andWhere('g.id = :id')
            ->setParameter('id', $id)
            ->select("
                g.groupName, MONTH(p.paymentDate) as mon, 
                YEAR(p.paymentDate) as yr, 
                SUM(p.amount) as total")
            ->groupBy('mon, yr')
            ->orderBy('yr, mon', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findYearlyGroupPayrolls($id)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.employeePayroll', 'e')
            ->innerJoin('e.employeeGroup', 'g')
            ->andWhere('g.id = :id')
            ->setParameter('id', $id)
            ->select("
                YEAR(p.paymentDate) as yr, 
                SUM(p.amount) as total")
            ->groupBy('yr')
            ->orderBy('yr')
            ->getQuery()
            ->getResult()
        ;
    }
    
}
