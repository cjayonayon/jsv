<?php

namespace App\Entity\Sysadmin;

use App\Entity\Admin\Group;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sysadmin\PayrollRepository")
 */
class Payroll
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank(message="Amount should not be blank.") 
     * @Assert\Positive
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Select a payment date.") 
     */
    private $paymentDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Select a starting date")
     * @Assert\Callback({"App\Validator\MaxDateValidator", "validate"})
     */
    private $startCoverage;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Select a ending date")
     * @Assert\Callback({"App\Validator\MinDateValidator", "validate"})
     */
    private $endCoverage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sysadmin\Employee", inversedBy="employeePayrolls")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Please select a group")
     */
    private $employeePayroll;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin\Group", inversedBy="payrolls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $groupPayroll;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(?\DateTimeInterface $paymentDate): self
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getStartCoverage(): ?\DateTimeInterface
    {
        return $this->startCoverage;
    }

    public function setStartCoverage(?\DateTimeInterface $startCoverage): self
    {
        $this->startCoverage = $startCoverage;

        return $this;
    }

    public function getEndCoverage(): ?\DateTimeInterface
    {
        return $this->endCoverage;
    }

    public function setEndCoverage(?\DateTimeInterface $endCoverage): self
    {
        $this->endCoverage = $endCoverage;

        return $this;
    }

    public function getEmployeePayroll(): ?Employee
    {
        return $this->employeePayroll;
    }

    public function setEmployeePayroll(?Employee $employeePayroll): self
    {
        $this->employeePayroll = $employeePayroll;

        return $this;
    }

    public function getGroupPayroll(): ?Group
    {
        return $this->groupPayroll;
    }

    public function setGroupPayroll(?Group $groupPayroll): self
    {
        $this->groupPayroll = $groupPayroll;

        return $this;
    }

}
