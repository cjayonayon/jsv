<?php

namespace App\Entity\Sysadmin;

use App\Entity\Admin\Group;
use App\Entity\EmployeeUser\EmployeeInvitation;
use App\Entity\EmployeeUser\EmployeeUser;
use App\Entity\EmployeeUser\Items;
use App\Entity\EmployeeUser\Queue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 * @ORM\Table(name="employee")
 * @UniqueEntity(fields="employeeId", message="Employee Id is already taken.")
 */
class Employee
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Last name should not be blank")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="First name should not be blank")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Middle name should not be blank")
     */
    private $middleName;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Birth date should not be blank")
     * @Assert\Range(
     *      max = "tomorrow",
     * )
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=255, name="employee_address")
     * @Assert\NotBlank(message="Address should not be blank")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Telephone number should not be blank")
     * @Assert\Regex(pattern="/^[(][0-9]{3}[)]\s\d{3}-\d{4}/", message="Follow the ff. format (Area Code) xxx-xxxx ex. (123) 456-7890") 
     */
    private $telNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Gender should not be blank")
     */
    private $gender;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Date Employed should not be blank")
     * @Assert\Range(
     *      max = "tomorrow",
     * )
     */
    private $dateEmployed;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank(message="Salary should not be blank")
     * @Assert\Positive
     */
    private $salary;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Employee ID should not be blank")
     */
    private $employeeId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin\Group", inversedBy="employees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employeeGroup;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sysadmin\Payroll", mappedBy="employeePayroll")
     */
    private $employeePayrolls;

    private $fullName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *      message = "The email '{{ value }}' is not a valid email.",
     *      checkMX = true
     *  )
     * @Assert\NotBlank(message="Email should not be blank")
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EmployeeUser\Items", mappedBy="employee")
     */
    private $employeeItems;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EmployeeUser\EmployeeUser", mappedBy="employeeId")
     */
    private $employeeUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EmployeeUser\EmployeeInvitation", mappedBy="employee")
     */
    private $employeeInvitations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EmployeeUser\Queue", mappedBy="employee")
     */
    private $queues;

    public function __construct()
    {
        $this->employeePayrolls = new ArrayCollection();
        $this->employeeItems = new ArrayCollection();
        $this->employeeInvitations = new ArrayCollection();
        $this->employeeUsers = new ArrayCollection();
        $this->queues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(?string $middleName): self
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTelNumber(): ?string
    {
        return $this->telNumber;
    }

    public function setTelNumber(?string $telNumber): self
    {
        $this->telNumber = $telNumber;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDateEmployed(): ?\DateTimeInterface
    {
        return $this->dateEmployed;
    }

    public function setDateEmployed(?\DateTimeInterface $dateEmployed): self
    {
        $this->dateEmployed = $dateEmployed;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(?string $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getEmployeeId(): ?string
    {
        return $this->employeeId;
    }

    public function setEmployeeId(?string $employeeId): self
    {
        $this->employeeId = $employeeId;

        return $this;
    }

    public function getEmployeeGroup(): ?Group
    {
        return $this->employeeGroup;
    }

    public function setEmployeeGroup(?Group $employeeGroup): self
    {
        $this->employeeGroup = $employeeGroup;

        return $this;
    }

    /**
     * @return Collection|Payroll[]
     */
    public function getEmployeePayrolls(): Collection
    {
        return $this->employeePayrolls;
    }

    public function addEmployeePayroll(Payroll $employeePayroll): self
    {
        if (!$this->employeePayrolls->contains($employeePayroll)) {
            $this->employeePayrolls[] = $employeePayroll;
            $employeePayroll->setEmployeePayroll($this);
        }

        return $this;
    }

    public function removeEmployeePayroll(Payroll $employeePayroll): self
    {
        if ($this->employeePayrolls->contains($employeePayroll)) {
            $this->employeePayrolls->removeElement($employeePayroll);
            // set the owning side to null (unless already changed)
            if ($employeePayroll->getEmployeePayroll() === $this) {
                $employeePayroll->setEmployeePayroll(null);
            }
        }

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->lastName.', '.$this->firstName.' '.$this->middleName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Items[]
     */
    public function getEmployeeItems(): Collection
    {
        return $this->employeeItems;
    }

    public function addEmployeeItem(Items $employeeItem): self
    {
        if (!$this->employeeItems->contains($employeeItem)) {
            $this->employeeItems[] = $employeeItem;
            $employeeItem->setEmployee($this);
        }

        return $this;
    }

    public function removeEmployeeItem(Items $employeeItem): self
    {
        if ($this->employeeItems->contains($employeeItem)) {
            $this->employeeItems->removeElement($employeeItem);
            // set the owning side to null (unless already changed)
            if ($employeeItem->getEmployee() === $this) {
                $employeeItem->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EmployeeUser[]
     */
    public function getEmployeeUsers(): Collection
    {
        return $this->employeeUsers;
    }

    public function addEmployeeUser(EmployeeUser $employeeUser): self
    {
        if (!$this->employeeUsers->contains($employeeUser)) {
            $this->employeeUsers[] = $employeeUser;
            $employeeUser->setEmployeeId($this);
        }

        return $this;
    }

    public function removeEmployeeUser(EmployeeUser $employeeUser): self
    {
        if ($this->employeeUsers->contains($employeeUser)) {
            $this->employeeUsers->removeElement($employeeUser);
            // set the owning side to null (unless already changed)
            if ($employeeUser->getEmployeeId() === $this) {
                $employeeUser->setEmployeeId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EmployeeInvitation[]
     */
    public function getEmployeeInvitations(): Collection
    {
        return $this->employeeInvitations;
    }

    public function addEmployeeInvitation(EmployeeInvitation $employeeInvitation): self
    {
        if (!$this->employeeInvitations->contains($employeeInvitation)) {
            $this->employeeInvitations[] = $employeeInvitation;
            $employeeInvitation->setEmployee($this);
        }

        return $this;
    }

    public function removeEmployeeInvitation(EmployeeInvitation $employeeInvitation): self
    {
        if ($this->employeeInvitations->contains($employeeInvitation)) {
            $this->employeeInvitations->removeElement($employeeInvitation);
            // set the owning side to null (unless already changed)
            if ($employeeInvitation->getEmployee() === $this) {
                $employeeInvitation->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Queue[]
     */
    public function getQueues(): Collection
    {
        return $this->queues;
    }

    public function addQueue(Queue $queue): self
    {
        if (!$this->queues->contains($queue)) {
            $this->queues[] = $queue;
            $queue->setEmployee($this);
        }

        return $this;
    }

    public function removeQueue(Queue $queue): self
    {
        if ($this->queues->contains($queue)) {
            $this->queues->removeElement($queue);
            // set the owning side to null (unless already changed)
            if ($queue->getEmployee() === $this) {
                $queue->setEmployee(null);
            }
        }

        return $this;
    }


}
