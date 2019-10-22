<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PayrollType;
use App\Entity\Sysadmin\Payroll;
use App\Entity\Sysadmin\Employee;

/**
 * @Route("/user/record-book")
 */
class PayrollController extends AbstractController
{
    /**
     * @Route("/payroll", name="payroll")
     */
    public function index(Request $request)
    {
        $data = $this->getUser();

        $payroll = new Payroll();

        $findEmployeesByGroup = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->findEmployeesByGroup($data->getGroup()->getId());

        $form = $this->createForm(PayrollType::class, $payroll, array(
            'user_employees' => $findEmployeesByGroup,
        ));
        
        $payroll->setGroupPayroll($data->getGroup());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($payroll);
            $entityManager->flush();

            $this->addFlash('notice', 'Payroll Successfully Registered');
            return $this->redirectToRoute('payroll');
        }
        return $this->render('record_book/payroll.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/report", name="report_info")
     */
    public function report()
    {
        $group = $this->getUser()->getGroup();
        
        $payrolls = $this->getDoctrine()
            ->getRepository(Payroll::class)
            ->sumOfAmountForPayroll($group);

        return $this->render('record_book/report.html.twig',[
            'payrolls' => $payrolls,
            'group' => $group
        ]);
    }

    /**
     * @Route("/report/view/monthly/{id}", name="view_employee_monthly_payroll")
     */
    public function viewMonthlyEmployeePayroll(string $id)
    {
        $payrolls = $this->getDoctrine()
            ->getRepository(Payroll::class)
            ->findEmployeePayrolls($id);

        $monthlyTotals = $this->getDoctrine()
            ->getRepository(Payroll::class)
            ->findMonthlyPayrolls($id);

        if(!$payrolls){
            throw $this->createNotFoundException("Page not Found");
        }

        return $this->render('record_book/payroll/monthly.html.twig',[
            'payrolls' => $payrolls,
            'monthlyTotals' => $monthlyTotals
        ]);
    }

    /**
     * @Route("/report/view/yearly/{id}", name="view_employee_yearly_payroll")
     */
    public function viewYearlyEmployeePayroll(string $id)
    {
        $yearlyTotals = $this->getDoctrine()
            ->getRepository(Payroll::class)
            ->findYearlyPayrolls($id);
        
        $monthlyTotals = $this->getDoctrine()
            ->getRepository(Payroll::class)
            ->findMonthlyPayrolls($id);
        
        if(!$monthlyTotals){
            throw $this->createNotFoundException("Page not Found");
        }

        return $this->render('record_book/payroll/yearly.html.twig',[
            'monthlyTotals' => $monthlyTotals,
            'yearlyTotals' => $yearlyTotals
        ]);
    }

    /**
     * @Route("/report/group", name="group_report_info")
     */
    public function groupReport()
    {
        return $this->render('record_book/payroll/group.html.twig');
    }

    /**
     * @Route("/report/group/monthly/{id}", name="view_group_monthly_payroll")
     */
    public function viewMonthlyGroupPayroll(string $id)
    {
        $monthlyTotals = $this->getDoctrine()
            ->getRepository(Payroll::class)
            ->findMonthlyGroupPayrolls($id);

        $totals = $this->getDoctrine()
            ->getRepository(Payroll::class)
            ->findMonthlyGroupTotal($id);

        return $this->render('record_book/payroll/groupMonthly.html.twig',[
            'monthlyTotals' => $monthlyTotals,
            'totals' => $totals
        ]);
    }

    /**
     * @Route("/report/group/yearly/{id}", name="view_group_yearly_payroll")
     */
    public function viewYearlyGroupPayroll(string $id)
    {
        $yearlyTotals = $this->getDoctrine()
            ->getRepository(Payroll::class)
            ->findYearlyGroupPayrolls($id);

        $monthlyTotals = $this->getDoctrine()
            ->getRepository(Payroll::class)
            ->findMonthlyGroupTotal($id);

        return $this->render('record_book/payroll/groupYearly.html.twig',[
            'monthlyTotals' => $monthlyTotals,
            'yearlyTotals' => $yearlyTotals
        ]);
    }
}
