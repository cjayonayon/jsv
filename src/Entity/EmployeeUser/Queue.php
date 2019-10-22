<?php

namespace App\Entity\EmployeeUser;

use App\Entity\Admin\Group;
use App\Entity\Sysadmin\Employee;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeUser\QueueRepository")
 */
class Queue
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sysadmin\Employee", inversedBy="queues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employee;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin\Group", inversedBy="queues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employeeGroup;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $videoId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EmployeeUser\Items", inversedBy="queues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $item;

    /**
     * @ORM\Column(type="integer")
     */
    private $startSeconds;

    public function __construct()
    {
        $this->startSeconds = 0;    
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmployeeGroup(): ?Group
    {
        return $this->employeeGroup;
    }

    public function setEmployeeGroup(?Group $employeeGroup): self
    {
        $this->employeeGroup = $employeeGroup;

        return $this;
    }

    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    public function setVideoId(string $videoId): self
    {
        $this->videoId = $videoId;

        return $this;
    }

    public function getItem(): ?Items
    {
        return $this->item;
    }

    public function setItem(?Items $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getStartSeconds(): ?int
    {
        return $this->startSeconds;
    }

    public function setStartSeconds(?int $startSeconds): self
    {
        $this->startSeconds = $startSeconds;

        return $this;
    }

}
