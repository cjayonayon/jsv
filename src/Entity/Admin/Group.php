<?php

namespace App\Entity\Admin;

use App\Entity\EmployeeUser\EmployeeInvitation;
use App\Entity\EmployeeUser\EmployeeUser;
use App\Entity\EmployeeUser\Items;
use App\Entity\EmployeeUser\Queue;
use App\Entity\Sysadmin\Employee;
use App\Entity\Sysadmin\Payroll;
use App\Entity\Sysadmin\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Admin\GroupRepository")
 * @ORM\Table(name="admin_group")
 */
class Group
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Group Name should not be blank")
     */
    private $groupName;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *          max = 500,
     *          maxMessage = "Group Description cannot be longer than {{ limit }} characters"
     * )
     * @Assert\NotBlank(message="Group Description should not be blank")
     */
    private $groupDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg", "image/jpg" })
     */
    private $groupBanner;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Employee limit should not be blank")
     * @Assert\Positive
     */
    private $employeeLimit;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Sysadmin\User", mappedBy="group", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sysadmin\Employee", mappedBy="employeeGroup")
     */
    private $employees;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Admin\Invitation", mappedBy="userGroup")
     */
    private $invitations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sysadmin\Payroll", mappedBy="groupPayroll")
     */
    private $payrolls;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EmployeeUser\EmployeeInvitation", mappedBy="employeeGroup")
     */
    private $employeeInvitations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EmployeeUser\EmployeeUser", mappedBy="employeeGroup")
     */
    private $employeeUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EmployeeUser\Items", mappedBy="itemGroup")
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EmployeeUser\Queue", mappedBy="employeeGroup")
     */
    private $queues;


    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->payrolls = new ArrayCollection();
        $this->employeeInvitations = new ArrayCollection();
        $this->employeeUsers = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->queues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function setGroupName(string $groupName): self
    {
        $this->groupName = $groupName;

        return $this;
    }

    public function getGroupDescription(): ?string
    {
        return $this->groupDescription;
    }

    public function setGroupDescription(string $groupDescription): self
    {
        $this->groupDescription = $groupDescription;

        return $this;
    }

    public function getGroupBanner()
    {
        return $this->groupBanner;
    }

    public function setGroupBanner($groupBanner): self
    {
        $this->groupBanner = $groupBanner;

        return $this;
    }

    public function getEmployeeLimit(): ?int
    {
        return $this->employeeLimit;
    }

    public function setEmployeeLimit(int $employeeLimit): self
    {
        $this->employeeLimit = $employeeLimit;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newGroupId = $user === null ? null : $this;
        if ($newGroupId !== $user->getGroupId()) {
            $user->setGroupId($newGroupId);
        }

        return $this;
    }

    /**
     * @return Collection|Employee[]
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
            $employee->setEmployeeGroup($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        if ($this->employees->contains($employee)) {
            $this->employees->removeElement($employee);
            // set the owning side to null (unless already changed)
            if ($employee->getEmployeeGroup() === $this) {
                $employee->setEmployeeGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Invitation[]
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): self
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations[] = $invitation;
            $invitation->setUserGroup($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): self
    {
        if ($this->invitations->contains($invitation)) {
            $this->invitations->removeElement($invitation);
            // set the owning side to null (unless already changed)
            if ($invitation->getUserGroup() === $this) {
                $invitation->setUserGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Payroll[]
     */
    public function getPayrolls(): Collection
    {
        return $this->payrolls;
    }

    public function addPayroll(Payroll $payroll): self
    {
        if (!$this->payrolls->contains($payroll)) {
            $this->payrolls[] = $payroll;
            $payroll->setGroupPayroll($this);
        }

        return $this;
    }

    public function removePayroll(Payroll $payroll): self
    {
        if ($this->payrolls->contains($payroll)) {
            $this->payrolls->removeElement($payroll);
            // set the owning side to null (unless already changed)
            if ($payroll->getGroupPayroll() === $this) {
                $payroll->setGroupPayroll(null);
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
            $employeeInvitation->setEmployeeGroup($this);
        }

        return $this;
    }

    public function removeEmployeeInvitation(EmployeeInvitation $employeeInvitation): self
    {
        if ($this->employeeInvitations->contains($employeeInvitation)) {
            $this->employeeInvitations->removeElement($employeeInvitation);
            // set the owning side to null (unless already changed)
            if ($employeeInvitation->getEmployeeGroup() === $this) {
                $employeeInvitation->setEmployeeGroup(null);
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
            $employeeUser->setEmployeeGroup($this);
        }

        return $this;
    }

    public function removeEmployeeUser(EmployeeUser $employeeUser): self
    {
        if ($this->employeeUsers->contains($employeeUser)) {
            $this->employeeUsers->removeElement($employeeUser);
            // set the owning side to null (unless already changed)
            if ($employeeUser->getEmployeeGroup() === $this) {
                $employeeUser->setEmployeeGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Items[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Items $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setItemGroup($this);
        }

        return $this;
    }

    public function removeItem(Items $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getItemGroup() === $this) {
                $item->setItemGroup(null);
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
            $queue->setEmployeeGroup($this);
        }

        return $this;
    }

    public function removeQueue(Queue $queue): self
    {
        if ($this->queues->contains($queue)) {
            $this->queues->removeElement($queue);
            // set the owning side to null (unless already changed)
            if ($queue->getEmployeeGroup() === $this) {
                $queue->setEmployeeGroup(null);
            }
        }

        return $this;
    }

}
