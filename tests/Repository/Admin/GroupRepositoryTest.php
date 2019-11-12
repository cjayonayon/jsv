<?php

namespace App\tests\Repository\Admin;

use App\Repository\Admin\GroupRepository;
use App\Entity\Admin\Group;
use Symfony\Component\Form\Test\TypeTestCase;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ManagerRegistry;

class GroupRepositoryTest extends TypeTestCase
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
        $mockGroup = new Group();
        $mockManagerRegistry->expects($this->any())
            ->method('getManagerForClass')
            ->will($this->returnValue($mockEm));

        $mockEm->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue($mockClassMetadata));

        new GroupRepository($mockManagerRegistry, $mockGroup);

    }
}