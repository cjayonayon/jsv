<?php

namespace App\tests\Repository;

use App\Repository\AdminRepository;
use App\Entity\Admin;
use Symfony\Component\Form\Test\TypeTestCase;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class AdminRepositoryTest extends TypeTestCase
{
    public function testIndex()
    {
        $this->assertTrue(true);
        $mockManagerRegistry = $this->getMockBuilder(ManagerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockClassMetadata = $this->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockEm = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockAdmin = new Admin();
        $mockManagerRegistry->expects($this->any())
            ->method('getManagerForClass')
            ->will($this->returnValue($mockEm));

        $mockEm->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue($mockClassMetadata));

        new AdminRepository($mockManagerRegistry, $mockAdmin);

    }
}