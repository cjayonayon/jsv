<?php

namespace App\tests\Entity;

use App\Entity\Admin;
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase
{
    public function testIndex()
    {
        $class = new Admin();
        $this->assertNull($class->getId());
        $class->setEmail('email');
        $this->assertEquals($class->getEmail(), 'email');
        $class->getUsername();
        $class->setUsername('user');
        $this->assertEquals($class->getUsername(), 'user');        
        $class->setPassword('password');
        $this->assertEquals($class->getPassword(), 'password');        
        $this->assertNotNull($class->getRoles());
        $this->assertNull($class->getSalt());
        $this->assertNull($class->eraseCredentials());
    }
}
