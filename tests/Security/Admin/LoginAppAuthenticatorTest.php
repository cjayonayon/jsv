<?php

namespace App\tests\Security\Admin;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;    
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Security\Admin\LoginAppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use App\Entity\Admin;
use ReflectionClass;

class LoginAppAuthenticatorTest extends TestCase
{
    private $mockEm;
    private $mockUrl;
    private $mockCsrf;
    private $mockUserPass;
    private $mockUserProvider;
    private $mockRepo;

    public function setUp()
    {
        $this->mockEm = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockUrl = $this->getMockBuilder(UrlGeneratorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockCsrf = $this->getMockBuilder(CsrfTokenManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockUserPass = $this->getMockBuilder(UserPasswordEncoderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockUserProvider = $this->getMockBuilder(UserProviderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockRepo = $this->createMock(ObjectRepository::class);
        
    }

    public function testLoginAppAuthenticator()
    {
        $mockRequest = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockSession = $this->getMockBuilder(SessionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects($this->any())
            ->method("getSession")
            ->will($this->returnValue($mockSession));
        $mockUserInterface = $this->getMockBuilder(UserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockToken = $this->getMockBuilder(TokenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockTokenManage = $this->getMockBuilder(CsrfTokenManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockTokenManage->expects($this->any())
            ->method('isTokenValid')
            ->willReturn(true);
        $mockParameterBag = $this->getMockBuilder(ParameterBag::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->attributes = $mockParameterBag;
        $mockRequest->request = $mockParameterBag;
        $mockRedirect = $this->getMockBuilder(RedirectResponse::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockCsrf->expects($this->any())
            ->method('istokenvalid')
            ->will($this->returnValue(true));

        $mockAdmin = new Admin();

        $this->mockRepo->expects($this->any())
			->method('findOneBy')
            ->will($this->returnValue($mockAdmin));
        $this->mockEm->expects($this->any())
			->method("getRepository")
            ->will($this->returnValue($this->mockRepo)); 
        
        $mockRedirect->expects($this->any())
            ->method('setTargetUrl')
            ->will($this->returnValue("admin"));
        $this->mockUrl->expects($this->any())
            ->method('generate')
            ->will($this->returnValue('admin'));
            
        $this->assertNotNull($authenticator = new LoginAppAuthenticator($this->mockEm, $this->mockUrl, $this->mockCsrf, $this->mockUserPass));
        $authenticator->supports($mockRequest);
        $authenticator->getCredentials($mockRequest);
        $authenticator->getUser( 1, $this->mockUserProvider);
        $authenticator->checkCredentials( 1, $mockUserInterface);
        $authenticator->onAuthenticationSuccess( $mockRequest, $mockToken, 'admin');


        $mockSession->expects($this->any())
            ->method('get')
            ->will($this->returnValue('_security.admin.target_path'));
        $authenticator->onAuthenticationSuccess( $mockRequest, $mockToken, 'admin');

        $reflector = new ReflectionClass(LoginAppAuthenticator::class);

        $method = $reflector->getMethod('getLoginUrl');
        $method->setAccessible(true);

        $result = $method->invoke($authenticator);

        $this->assertNotNull($result);
        
    }

    public function testException()
    {
        $this->assertNotNull($authenticator = new LoginAppAuthenticator($this->mockEm, $this->mockUrl, $this->mockCsrf, $this->mockUserPass));
        $this->expectException(InvalidCsrfTokenException::class);
        $authenticator->getUser( 1, $this->mockUserProvider);
    }

    public function testException2()
    {
        $this->mockCsrf->expects($this->any())
            ->method('istokenvalid')
            ->will($this->returnValue(true));

        $this->mockRepo->expects($this->any())
			->method('findOneBy')
            ->will($this->returnValue(null));
        $this->mockEm->expects($this->any())
			->method("getRepository")
            ->will($this->returnValue($this->mockRepo)); 
        
        $this->assertNotNull($authenticator = new LoginAppAuthenticator($this->mockEm, $this->mockUrl, $this->mockCsrf, $this->mockUserPass));
        $this->expectException(CustomUserMessageAuthenticationException::class);
        $authenticator->getUser( 1, $this->mockUserProvider);
    }



}
