<?php

namespace App\tests\Controller\Admin;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Registry;
use App\Controller\Admin\GroupController;
use Symfony\Component\Form\Form;
use PHPUnit\Framework\TestCase;
use App\Entity\Admin\Group;
use Twig\Environment;

class GroupControllerTest extends TestCase
{
    private $mockRequest;
    private $mockDoctrine;
    private $mockObjectManager;
    private $mockForm;
    private $mockFormFactory;
    private $mockTemplating;
    private $mockSession;
    private $mockSessionFlash;
    private $mockContainer;
    private $controller;
    private $mockRouter;
    private $mockBag;

    public function setUp()
    {
        $this->mockRequest = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockDoctrine = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockObjectManager = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockForm = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockFormFactory = $this->getMockBuilder(FormFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockTemplating = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockSession = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockSessionFlash = $this->getMockBuilder(FlashBagInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockContainer = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockRouter = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockBag = $this->getMockBuilder(ContainerBagInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->controller = new GroupController;
        $this->mockContainer->expects($this->any())
            ->method('has')
            ->will($this->returnValue(true));
        
    }

    public function testController()
    {
        $this->mockDoctrine->expects($this->any())
			->method("getManager")
            ->will($this->returnValue($this->mockObjectManager));
        $this->mockFormFactory->expects($this->any())
            ->method('create')
            ->will($this->returnValue($this->mockForm));
        $this->mockSession->expects($this->any())
            ->method("getFlashBag")
            ->will($this->returnValue($this->mockSessionFlash));
        $this->mockRouter->expects($this->any())
            ->method('generate')
            ->will($this->returnValue(true));
        $map = array(
            array('doctrine', 1, $this->mockDoctrine),
            array('form.factory', 1,$this->mockFormFactory),
            array('templating',1,$this->mockTemplating),
            array('router',1,$this->mockRouter),
            array('session', 1, $this->mockSession),
            array('parameter_bag', 1, $this->mockBag),
            ) ;

        $this->mockContainer->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($map));
            
        $this->controller->setContainer($this->mockContainer);
        $this->assertNotNull($this->controller->index($this->mockRequest));

        $mockGroup = $this->createMock(Group::class);

        $mockRepo = $this->createMock(ObjectRepository::class);
        $mockRepo->expects($this->any())
			->method('findAll')
            ->will($this->returnValue($mockGroup));
        $mockRepo->expects($this->any())
			->method('find')
            ->will($this->returnValue($mockGroup));
        $this->mockObjectManager->expects($this->any())
			->method("remove")
            ->will($this->returnValue($mockGroup));   
        $this->mockDoctrine->expects($this->any())
			->method("getRepository")
            ->will($this->returnValue($mockRepo));  

        $this->assertNotNull($this->controller->editGroup(1,$this->mockRequest));
        
        $this->mockForm->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));
        $this->mockForm->expects($this->any())
            ->method('isSubmitted')
            ->will($this->returnValue(true));

        $this->assertNotNull($this->controller->editGroup(1,$this->mockRequest));

        $this->mockForm->expects($this->any())
            ->method('getData')
            ->will($this->returnValue($mockGroup));

        $mockUploadedFile = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockGroup->expects($this->any())
            ->method('getGroupBanner')
            ->will($this->returnValue($mockUploadedFile));
        $mockUploadedFile->expects($this->any())
            ->method('guessExtension')
            ->will($this->returnValue('jpg'));

        $this->assertNotNull($this->controller->index($this->mockRequest));
        $this->assertNotNull($this->controller->viewGroup());
        $this->assertNotNull($this->controller->editGroup(1,$this->mockRequest));
        $this->assertNotNull($this->controller->deleteInvitation(1,$this->mockRequest));
    }

    public function testException()
    {
        $this->mockContainer->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->mockDoctrine));

        $mockRepo = $this->createMock(ObjectRepository::class);
        $mockRepo->expects($this->any())
			->method('find')
            ->will($this->returnValue(null));
        $this->mockDoctrine->expects($this->any())
			->method("getRepository")
            ->will($this->returnValue($mockRepo)); 
        

        $this->controller->setContainer($this->mockContainer);
        $this->expectException(NotFoundHttpException::class);
        $this->assertNotNull($this->controller->deleteInvitation(1,$this->mockRequest));
    }

    public function testExceptionEditGroup()
    {
        $this->mockContainer->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->mockDoctrine));

        $mockRepo = $this->createMock(ObjectRepository::class);
        $mockRepo->expects($this->any())
			->method('find')
            ->will($this->returnValue(null));
        $this->mockDoctrine->expects($this->any())
			->method("getRepository")
            ->will($this->returnValue($mockRepo));  

        $this->controller->setContainer($this->mockContainer);
        $this->expectException(NotFoundHttpException::class);
        $this->assertNotNull($this->controller->editGroup(1,$this->mockRequest));
    }

}
