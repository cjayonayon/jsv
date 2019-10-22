<?php

namespace App\Controller\User;

use App\Entity\Sysadmin\Employee;
use App\Entity\Sysadmin\User;
use App\Form\EmployeeType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Psr\Log\LoggerInterface;
use App\Entity\EmployeeUser\EmployeeUser;
use App\Entity\EmployeeUser\EmployeeInvitation;

/**
 * @Route("/user/record-book")
 */
class RecordBookController extends AbstractController
{
    /**
     * @Route("/", name="record_book")
     */
    public function index()
    {
        $data = $this->getUser();
        $numberOfEmployees = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->findEmployeeCount($data->getGroup()->getId());

        return $this->render('record_book/index.html.twig', [
            'user' => $data,
            'ctr' => $numberOfEmployees
        ]);
    }

    /**
     * @Route("/employee", name="employee_info")
     */
    public function employee(){
        $counter = 0;
        $employees = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->findBy(['employeeGroup'=>$this->getUser()->getGroup()->getId()]);
        
        $counter = count($employees);

        return $this->render('record_book/employee.html.twig',[
            'employees' => $employees,
            'ctr' => $counter
        ]);
    }

    /**
     * @Route("/view/{id}", name="view_employee_info")
     */
    public function view(int $id)
    {
        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->find($id);
        
        if(!$employee){
            throw $this->createNotFoundException("Page not Found");
        }

        return $this->render('record_book/view.html.twig', [ 
            'employeeInfo' => $employee,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_employee_info")
     */
    public function delete(int $id, Request $request)
    {
        if(!$request->isXmlHttpRequest()){
           throw $this->createAccessDeniedException("Invalid Request");
        }

        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->find($id);

        if(!$employee){
            throw $this->createNotFoundException("Page not Found");
        }
            
        $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($employee);
            $entityManager->flush();

        return $this->redirectToRoute('record_book');
    }

    /**
     * @Route("/create", name="create_employee_info")
     */
    public function create(Request $request)
    {
        
        $employee = new Employee();
        
        $form = $this->createForm(EmployeeType::class,$employee);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $employee->setEmployeeGroup($this->getUser()->getGroup());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($employee);
            $entityManager->flush();

            return $this->redirectToRoute('employee_info');
        }

        return $this->render('record_book/create.html.twig', [
            'form' => $form->createView(), 
        ]);
    }

    /**
     * @Route("/update/{id}", name="update_employee_info")
     */
    public function update(Request $request, int $id){
        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->find($id);

        if(!$employee){
            throw $this->createNotFoundException("Page not Found");
        }
        
        $form = $this->createForm(EmployeeType::class,$employee);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($employee);
            $entityManager->flush();

            return $this->redirectToRoute('record_book');
        }

        return $this->render('record_book/create.html.twig', [
            'form' => $form->createView(), 
        ]);
    }

    /**
     * @Route("/intro/{username}", name="update_user")
     */
    public function intro(Request $request, string $username, UserPasswordEncoderInterface $passwordEncoder)
    {
        
        $userEntity = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['username'=>$username]);

            if(!$userEntity){
                throw $this->createNotFoundException("Page not Found");
            }

            $form = $this->createForm(UserType::class,$userEntity);
    
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){

                $userEntity->setPassword(
                    $passwordEncoder->encodePassword(
                        $userEntity,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($userEntity);
                $entityManager->flush();
    
                return $this->redirectToRoute('record_book');
            }

        return $this->render('record_book/intro.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/send/employee/{id}", name="send_employee_invitation")
     */
    public function employeeInvitation(Request $request, \Swift_Mailer $mailer, LoggerInterface $logger, int $id)
    {
        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->findOneBy(['id'=>$id]);
        
        $from = $this->getUser()->getEmail();
        $to = $employee->getEmail();
        $username = explode('@', $to);
        $password = substr(md5(uniqid(rand(), true)), 0, 5);

        $userEmployee = new EmployeeInvitation();

        $userEmployee->setEmail($to);
        $userEmployee->setPassword($password);
        $userEmployee->setUsername($username[0]);
        $userEmployee->setEmployeeGroup($employee->getEmployeeGroup());
        $userEmployee->setEmployee($employee);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userEmployee);
        $entityManager->flush();


        $message = new \Swift_Message('Invitation Email');
        $message->setFrom($from);
        $message->setTo($to);
        $message->setBody(
            $this->renderView(
                'employee/mail.html.twig',
                [
                    'username' => $username[0],
                    'password' => $password,
                    'id' => $id,
                ]
            ),
            'text/html'
        );
        $mailer->send($message);
        
            $this->addFlash('notice', 'Email Successfully Sent');        
        
        return $this->redirectToRoute('employee_info');
    }
}