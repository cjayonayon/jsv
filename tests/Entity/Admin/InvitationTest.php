<?php

namespace App\tests\Entity\Admin;

use App\Entity\Admin\Invitation;
use PHPUnit\Framework\TestCase;
use App\Entity\Admin\Group;

class InvitationTest extends TestCase
{
    public function testIndex()
    {
        $class = new Invitation();
        $this->assertNull($class->getId());
        $this->assertNotNull($class->setEmail('email'));
        $this->assertEquals($class->getEmail(), 'email');
        $this->assertNotNull($class->setUsername('user'));
        $this->assertEquals($class->getUsername(), 'user');
        $this->assertNotNull($class->setPassword('password'));
        $this->assertEquals($class->getPassword(), 'password');
        $this->assertNotNull($class->setStatus('Accepted'));
        $this->assertEquals($class->getStatus(), 'Accepted');
        $this->assertNotNull($class->getInvitedAt());
        $this->assertNotNull($class->setInvitedAt($class->getInvitedAt()));
        $mockUserGroup = $this->createMock(Group::class);
        $this->assertNotNull($class->setUserGroup($mockUserGroup));
        $this->assertEquals($class->getUserGroup(), $mockUserGroup);
        $this->assertNotNull($class->getLongId());
        $this->assertNotNull($class->checkExpiration());
    }
}
