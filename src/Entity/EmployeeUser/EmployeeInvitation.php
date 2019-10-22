<?php

namespace App\Entity\EmployeeUser;

use App\Entity\Admin\Group;
use App\Entity\Sysadmin\Employee;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeUser\EmployeeInvitationRepository")
 */
class EmployeeInvitation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $invitedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin\Group", inversedBy="employeeInvitations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employeeGroup;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sysadmin\Employee", inversedBy="employeeInvitations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employee;


    public function __construct()
    {
        $this->status = 'Pending';
        $this->invitedAt = new \DateTime("now", new \DateTimeZone('Asia/Manila'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getInvitedAt(): ?\DateTimeInterface
    {
        return $this->invitedAt;
    }

    public function setInvitedAt(\DateTimeInterface $invitedAt): self
    {
        $this->invitedAt = $invitedAt;

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

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

}
