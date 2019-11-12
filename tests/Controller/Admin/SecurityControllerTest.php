<?php

namespace App\tests\Controller\Admin;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Controller\Admin\SecurityController;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class SecurityControllerTest extends TestCase
{
    public function testIndex()
    {   
        $mockUtil = $this->getMockBuilder(AuthenticationUtils::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockContainer = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockContainer->expects($this->once())
            ->method('has')
            ->will($this->returnValue(true));
        $mockTemplating = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockContainer->expects($this->at(1))
            ->method('get')
            ->with('templating')
            ->will($this->returnValue($mockTemplating));
            
        $controller = new SecurityController;
        $controller->setContainer($mockContainer);
        $this->assertNotNull($controller->login($mockUtil));
    }

    public function testLogout()
    {
        $controller = new SecurityController();
        $this->expectException(\Exception::class);
        $this->assertNotNull($controller->logout());
    }
}
