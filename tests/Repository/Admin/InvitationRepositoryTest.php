<?php

namespace App\tests\Repository\Admin;

use App\Repository\Admin\InvitationRepository;
use App\Entity\Admin\Invitation;
use Symfony\Component\Form\Test\TypeTestCase;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;

class InvitationRepositoryTest extends TypeTestCase
{
    public function testIndexOne()
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

        $mockGroup = new Invitation();

        $mockManagerRegistry->expects($this->any())
            ->method('getManagerForClass')
            ->will($this->returnValue($mockEm));
        
        $mockEm->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue($mockClassMetadata));

        $mockRepo = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockQueryBuidler = $this->createMock(QueryBuilder::class);
        $mockQuery = $this->createMock(AbstractQuery::class);
            
        $mockEm->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($mockRepo));
        $mockRepo->expects($this->any())
            ->method('createQueryBuilder')
            ->will($this->returnValue($mockQueryBuidler));
        $mockEm->expects($this->any())
            ->method('createQueryBuilder')
            ->will($this->returnValue($mockQueryBuidler));
        $mockQueryBuidler->expects($this->any())
            ->method('innerjoin')
            ->will($this->returnValue($mockQueryBuidler));
        $mockQueryBuidler->expects($this->any())
            ->method('select')
            ->will($this->returnValue($mockQueryBuidler));
        $mockQueryBuidler->expects($this->any())
            ->method('from')
            ->will($this->returnValue($mockQueryBuidler));
        $mockQueryBuidler->expects($this->any())
            ->method('andWhere')
            ->will($this->returnValue($mockQueryBuidler));
        $mockQueryBuidler->expects($this->any())
            ->method('join')
            ->will($this->returnValue($mockQueryBuidler));
        $mockQueryBuidler->expects($this->any())
            ->method('setParameter')
            ->will($this->returnValue($mockQueryBuidler));
        $mockQueryBuidler->expects($this->any())
            ->method('orderBy')
            ->will($this->returnValue($mockQueryBuidler));
        $mockQueryBuidler->expects($this->any())
            ->method('getQuery')
            ->will($this->returnValue($mockQuery));

        $repository = new InvitationRepository($mockManagerRegistry, $mockGroup);
        $repository->findInvitedAt(1);
        $repository->findMaxInvitedAt(1);
    }
}


















