<?php

namespace App\tests\Controller\Admin;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Controller\Admin\AdminController;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class AdminControllerTest extends TestCase
{
    private $mockContainer;
    private $mockTemplating;

    public function setUp()
    {
        $this->mockContainer = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockTemplating = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testIndex()
    {
        $this->mockContainer->expects($this->once())
            ->method('has')
            ->will($this->returnValue(true));
        $this->mockContainer->expects($this->any())
            ->method('get')
            ->with('templating', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)
            ->will($this->returnValue($this->mockTemplating));
        $controller = new AdminController;
        $controller->setContainer($this->mockContainer);
        $this->assertNotNull($controller->index());
    }
}
