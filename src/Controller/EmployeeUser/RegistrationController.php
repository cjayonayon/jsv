<?php

namespace App\Controller\EmployeeUser;

use App\Entity\EmployeeUser\EmployeeInvitation;
use App\Entity\EmployeeUser\EmployeeUser;
use App\Form\EmployeeUser\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Security\User\LoginAppAuthenticator;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register/{id}", name="employee_register")
     */
    public function register(string $id, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginAppAuthenticator $authenticator): Response
    {
        $employeeInvitation = $this->getDoctrine()
            ->getRepository(EmployeeInvitation::class)
            ->findOneBy(['employee'=>$id]);
        
        $employee = $this->getDoctrine()
            ->getRepository(EmployeeUser::class)
            ->findOneBy(['employeeId'=>$id]);

        if (!$employee) {
            $employee = new EmployeeUser();
                
            $employee->setUsername($employeeInvitation->getUsername());
            $employee->setEmail($employeeInvitation->getEmail());
            $employee->setPlainPassword($employeeInvitation->getPassword());
            $employee->setPassword('');
            $employee->setEmployeeGroup($employeeInvitation->getEmployeeGroup());
            $employee->setEmployeeId($employeeInvitation->getEmployee());
        }
        
        $form = $this->createForm(RegistrationFormType::class, $employee);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $employee->setPassword(
                $passwordEncoder->encodePassword(
                    $employee,
                    $form->get('password')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($employee);
                $entityManager->flush();

            $employeeInvitation->setStatus('Accepted');

            $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($employeeInvitation);
                $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('user_employee');

            return $guardHandler->authenticateUserAndHandleSuccess(
                $employee,
                $request,
                $authenticator,
                'employee' // firewall name in security.yaml
            );
        }

        return $this->render('employee/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/verify/{id}", name="verify_employee_email")
     */
    public function verifyEmployeEmail($id)
    {
        $employeeUser = $this->getDoctrine()
            ->getRepository(EmployeeInvitation::class)
            ->findOneBy(['employee'=>$id]);

        if(!$employeeUser){
            throw $this->createNotFoundException("Page not Found");
        }elseif($employeeUser->getStatus() == 'Accepted'){
            throw $this->createAccessDeniedException("User has already registered");
        }

        return $this->redirectToRoute('employee_register',['id' => $id]);
    }
}
