<?php

namespace App\tests\Entity\Admin;

use App\Entity\EmployeeUser\EmployeeInvitation;
use App\Entity\EmployeeUser\EmployeeUser;
use App\Entity\EmployeeUser\Items;
use App\Entity\EmployeeUser\Queue;
use App\Entity\Sysadmin\Employee;
use App\Entity\Sysadmin\Payroll;
use App\Entity\Admin\Invitation;
use PHPUnit\Framework\TestCase;
use App\Entity\Sysadmin\User;
use App\Entity\Admin\Group;

class GroupTest extends TestCase
{
    public function testIndex()
    {
        $class = new Group();
        $this->assertNull($class->getId());
        $this->assertNotNull($class->setGroupName('group name'));
        $this->assertEquals($class->getGroupName(), 'group name');
        $class->setGroupDescription('GroupDescription');
        $this->assertEquals($class->getGroupDescription(), 'GroupDescription');
        $class->setGroupBanner('GroupBanner');
        $this->assertEquals($class->getGroupBanner(), 'GroupBanner');
        $class->setEmployeeLimit(1);
        $this->assertEquals($class->getEmployeeLimit(), 1);        
        $this->assertNull($class->getUser());
        $mockUser = $this->createMock(User::class);
        $class->setUser($mockUser);       
        $class->getEmployees();

        $mockEmployee = $this->createMock(Employee::class);
        $class->addEmployee($mockEmployee);
        $mockEmployee->expects($this->any())
            ->method('getEmployeeGroup')
            ->willReturn($class);
        $class->removeEmployee($mockEmployee);
        $this->assertNotNull($class->removeEmployee($mockEmployee));
        $class->getInvitations();

        $mockInvitation = $this->createMock(Invitation::class);
        $class->addInvitation($mockInvitation);
        $mockInvitation->expects($this->any())
            ->method('getUserGroup')
            ->willReturn($class);
        $class->removeInvitation($mockInvitation);
        $class->getPayrolls();

        $mockPayroll = $this->createMock(Payroll::class);
        $class->addPayroll($mockPayroll);
        $mockPayroll->expects($this->any())
            ->method('getGroupPayroll')
            ->willReturn($class);
        $class->removePayroll($mockPayroll);
        $class->getEmployeeInvitations();

        $mockEmployeeInvitation = $this->createMock(EmployeeInvitation::class);
        $class->addEmployeeInvitation($mockEmployeeInvitation);
        $mockEmployeeInvitation->expects($this->any())
            ->method('getEmployeeGroup')
            ->willReturn($class);
        $class->removeEmployeeInvitation($mockEmployeeInvitation);
        $class->getEmployeeUsers();

        $mockEmployeeUser = $this->createMock(EmployeeUser::class);
        $class->addEmployeeUser($mockEmployeeUser);
        $mockEmployeeUser->expects($this->any())
            ->method('getEmployeeGroup')
            ->willReturn($class);
        $class->removeEmployeeUser($mockEmployeeUser);
        $class->getItems();

        $mockItems = $this->createMock(Items::class);
        $this->assertNotNull($class->addItem($mockItems));
        $mockItems->expects($this->any())
            ->method('getItemGroup')
            ->willReturn($class);
        $this->assertNotNull($class->removeItem($mockItems));
        $class->getQueues();

        $mockQueue = $this->createMock(Queue::class);
        $this->assertNotNull($class->addQueue($mockQueue));
        $mockQueue->expects($this->any())
            ->method('getEmployeeGroup')
            ->willReturn($class);
        $this->assertNotNull($class->removeQueue($mockQueue));
        
    }
}
