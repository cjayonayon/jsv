<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Admin\Invitation;
use App\Entity\Sysadmin\Employee;
use App\Form\InvitationType;
use Psr\Log\LoggerInterface;

/**
 * @Route("/admin/invitation")
 */
class InvitationController extends AbstractController
{
    /**
     * @Route("/", name="view_invitation")
     */
    public function index()
    {
        $userInvitations = $this->getDoctrine()
            ->getRepository(Invitation::class)
            ->findAll();
        
        return $this->render('admin/invitation/index.html.twig',[
            'userInvitations' => $userInvitations,
        ]);
    }

    /**
     * @Route("/send", name="send_invitation")
     */
    public function invitation(Request $request, \Swift_Mailer $mailer, LoggerInterface $logger)
    {

        $userInvitation = new Invitation();

        $form = $this->createForm(InvitationType::class,$userInvitation);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $email = $userInvitation->getEmail();//has email  
            $this->composeMail($userInvitation, $mailer, $email, $logger);
            $this->addFlash('notice', 'Email Successfully Sent');        
        }

        return $this->render('admin/invitation/invite.html.twig',[
            'form' => $form->createView(),
            ]);
    }
        
    /**
     * @Route("/delete/{longId}", name="delete_invitation")
     */
    public function deleteInvitation(string $longId){
        $invitation = $this->getDoctrine()
            ->getRepository(Invitation::class)
            ->findOneBy(['longId'=>$longId]);

        if(!$invitation){
            throw $this->createNotFoundException("Page not Found");
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($invitation);
        $entityManager->flush();
        
        return $this->redirectToRoute('view_invitation');
    }

    /**
     * @Route("/resend/{longId}", name="resend_invitation")
     */
    public function resendInvitation(string $longId, \Swift_Mailer $mailer)
    {
        $invitation = $this->getDoctrine()
            ->getRepository(Invitation::class)
            ->findOneBy(['longId'=>$longId]);

        if(!$invitation){
            throw $this->createNotFoundException("Page not Found");
        }
        
        $invitation->setInvitedAt(new \DateTime("now", new \DateTimeZone('Asia/Manila')));
        $password = substr(md5(uniqid(rand(), true)), 0, 5);
        $invitation->setPassword($password);

            $this->sendMail($invitation, $mailer);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($invitation);
            $entityManager->flush();

            $this->addFlash('notice', 'Email Successfully Sent');

        return $this->redirectToRoute('view_invitation');
    }

    private function sendMail(Invitation $userInvitation, \Swift_Mailer $mailer)
    {
        $message = new \Swift_Message('Invitation Email');
        $message->setFrom('admin@gmail.com');
        $message->setTo($userInvitation->getEmail());
        $message->setBody(
            $this->renderView(
                'admin/mail.html.twig',
                [
                    'username' => $userInvitation->getUsername(),
                    'groupName' => $userInvitation->getUserGroup()->getGroupName(),
                    'password' => $userInvitation->getPassword(),
                    'longId' => $userInvitation->getLongId(),
                ]
            ),
            'text/html'
        );
        $mailer->send($message);
        
    }

    private function composeMail(Invitation $userInvitation, \Swift_Mailer $mailer, string $email, LoggerInterface $logger)
    {
        $user = explode('@', $email);
        $username = $user[0];//username
        $password = substr(md5(uniqid(rand(), true)), 0, 5);
        $userInvitation->setUsername($username);
        $userInvitation->setPassword($password);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userInvitation);
        $entityManager->flush();
        
        $this->sendMail($userInvitation, $mailer);

        $logger->info('Email Successfully Sent.');
    }
}
