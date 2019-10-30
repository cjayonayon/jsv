<?php

namespace App\Controller\User;

use App\Entity\Admin\Invitation;
use App\Entity\Sysadmin\User;
use App\Form\UserType;
use App\Security\User\LoginAppAuthenticator;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register/{longId}", name="app_register")
     */
    public function register(string $longId, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginAppAuthenticator $authenticator): Response
    {
        $userEntity = $this->getDoctrine()
            ->getRepository(Invitation::class)
            ->findOneBy(['longId'=>$longId]);

        if(!$userEntity){
            throw $this->createNotFoundException("Page not Found");
        }
        // dd($userEntity);
        $user = new User();
        $user->setUsername($userEntity->getUsername());
        $user->setEmail($userEntity->getEmail());
        $user->setGroup($userEntity->getUserGroup());
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $userEntity->setStatus('Accepted');
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_login');
            
            // do anything else you need here, like send an email
            
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }
        
        return $this->render('record_book/intro.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
    * @Route("/accept-invitation/{longId}", name="accept_invitation")
    */
    public function acceptInvitation(string $longId)
    {
        $userEntity = $this->getDoctrine()
            ->getRepository(Invitation::class)
            ->findOneBy(['longId'=>$longId]);
        
        if(!$userEntity){
            throw $this->createNotFoundException("Page not Found");
        }
        // dd($userEntity->getInvitedAt()->format('U') - (new \DateTime('now',new \DateTimeZone('Asia/Manila')))->format('U') < 0 );
        if($userEntity->checkExpiration()){
            throw $this->createAccessDeniedException('Invitation has already been expired.');            
        }

        $invitedUser = $userEntity->getUsername();

        $usera = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['username'=>$invitedUser]);

        $userAll = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['group'=>$userEntity->getUserGroup()]);

        if($usera){
            throw $this->createAccessDeniedException('Username already taken.');
        }

        if($userAll){
            throw $this->createAccessDeniedException('Group is already taken.');
        }

        $userEntitys = $this->getDoctrine()
            ->getRepository(Invitation::class)
            ->findBy(['userGroup'=>$userEntity->getUserGroup()->getId()]);

        foreach($userEntitys as $user){
            if($user->getStatus() == "Accepted"){
                throw $this->createAccessDeniedException('Someone has already accepted the invitation.');            
            }
        }    

        $maxInvitedAt = $this->getDoctrine()
            ->getRepository(Invitation::class)
            ->findMaxInvitedAt($userEntity->getUserGroup()->getId());

        $InvitedAt = $this->getDoctrine()
            ->getRepository(Invitation::class)
            ->findInvitedAt($longId);

        if($InvitedAt < $maxInvitedAt){
            throw $this->createAccessDeniedException('Invitation is not the latest invitation.');            
        }

        return $this->redirectToRoute('app_register',['longId' => $longId]);
    }
}