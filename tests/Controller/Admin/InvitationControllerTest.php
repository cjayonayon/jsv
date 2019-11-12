<?php

namespace App\tests\Controller\Admin;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\FormFactoryInterface;
use App\Controller\Admin\InvitationController;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Registry;
use App\Entity\Admin\Invitation;
use Symfony\Component\Form\Form;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use App\Entity\Admin\Group;
use Twig\Environment;
use Swift_Mailer;

class InvitationControllerTest extends TestCase
{
    private $controller;
    private $mockRequest;
    private $mockContainer;
    private $mockDoctrine;
    private $mockTemplating;
    private $mockRouter;
    private $mockForm;
    private $mockFormFactory;
    private $mockSession;
    private $mockSessionFlash;
    private $mockInvitation;
    private $mockGroup;
    private $mockObjectManager;
    private $mockMailer;
    private $mockLogger;
    private $mockRepo;

    public function setUp()
    {
        $this->controller = new InvitationController();
        
        $this->mockRequest = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockContainer = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockDoctrine = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockTemplating = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockRouter = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockForm = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockFormFactory = $this->getMockBuilder(FormFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockSession = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockSessionFlash = $this->getMockBuilder(FlashBagInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockObjectManager = $this->createMock(ObjectManager::class);        
        $this->mockMailer = $this->createMock(Swift_Mailer::class);
        $this->mockLogger = $this->createMock(LoggerInterface::class);
        $this->mockRepo = $this->createMock(ObjectRepository::class);
        $this->mockInvitation = new Invitation;
        
        $this->mockContainer->expects($this->any())
            ->method('has')
            ->will($this->returnValue(true));
        $this->mockDoctrine->expects($this->any())
			->method("getRepository")
			->will($this->returnValue($this->mockRepo));    
    }

    public function testInvitationController()
    {
        $this->mockTemplating->expects($this->any())
            ->method('render')
            ->will($this->returnValue(''));
        $this->mockRouter->expects($this->any())
            ->method('generate')
            ->will($this->returnValue(true));
        $this->mockFormFactory->expects($this->any())
            ->method('create')
            ->will($this->returnValue($this->mockForm)); 
        $this->mockSession->expects($this->any())
            ->method("getFlashBag")
            ->will($this->returnValue($this->mockSessionFlash));
            
        $map = [
            ['doctrine', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,  $this->mockDoctrine],
            ['templating', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $this->mockTemplating],
            ['session', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $this->mockSession],
            ['router', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $this->mockRouter],
            ['form.factory', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $this->mockFormFactory],
        ] ;  
        
        //invitation method
        // $this->mockContainer->expects($this->any())
        //     ->method('get')
        //     ->withConsecutive(['form.factory', 1], ['doctrine',1], ['templating', 1], ['session', 1], ['templating', 1] )
        //     ->willReturnOnConsecutiveCalls($this->mockFormFactory, $this->mockDoctrine, $this->mockTemplating, $this->mockSession, $this->mockTemplating);

        $this->mockContainer->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($map));

        $this->mockRepo->expects($this->any())
			->method('findAll')
            ->will($this->returnValue($this->mockInvitation));
        $this->mockRepo->expects($this->any())
			->method('findOneBy')
            ->will($this->returnValue($this->mockInvitation));
        
        $this->mockDoctrine->expects($this->any())
			->method("getManager")
            ->will($this->returnValue($this->mockObjectManager));
        $this->mockObjectManager->expects($this->any())
			->method("remove")
            ->will($this->returnValue($this->mockInvitation));     
        $this->mockForm->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));
        $this->mockForm->expects($this->any())
            ->method('isSubmitted')
            ->will($this->returnValue(true));

        $this->controller->setContainer($this->mockContainer);
        $this->assertNotNull($this->controller->index());
        $this->assertNotNull($this->controller->invitation($this->mockRequest,$this->mockMailer,$this->mockLogger));
        $this->assertNotNull($this->controller->deleteInvitation('1'));
        $this->assertNotNull($this->controller->resendInvitation('1',$this->mockMailer));
    }

    public function testException()
    {
        $this->mockContainer->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->mockDoctrine));

        $this->mockRepo->expects($this->any())
			->method('findOneBy')
            ->will($this->returnValue(null));

        $this->controller->setContainer($this->mockContainer);
        $this->expectException(NotFoundHttpException::class);
        $this->assertNull($this->controller->resendInvitation('1',$this->mockMailer));

    }

    public function testExceptionTwo()
    {
        $this->mockContainer->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->mockDoctrine));

        $this->mockRepo->expects($this->any())
			->method('findOneBy')
            ->will($this->returnValue(null));
        
        $this->controller->setContainer($this->mockContainer);
        $this->expectException(NotFoundHttpException::class);
        $this->assertNull($this->controller->deleteInvitation('1'));
    }
}
