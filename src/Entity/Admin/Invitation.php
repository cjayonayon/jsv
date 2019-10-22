<?php

namespace App\Entity\Admin;

use App\Entity\Admin\Group;
use App\Validator\UserInvitation;
use App\Validator\GroupInvitation;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Admin\InvitationRepository")
 */
class Invitation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="guid", unique=true, nullable=false, name="long_id")
     */
    protected $longId;

    /**
     * @ORM\Column(type="string", length=255)
     * @UserInvitation()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, name="plain_password")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, name="invitation_status")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $invitedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin\Group", inversedBy="invitations")
     * @ORM\JoinColumn(nullable=false)
     * @GroupInvitation()
     */
    private $userGroup;

    public function __construct()
    {  
       $this->status = 'Pending';
       $this->invitedAt = new \DateTime("now", new \DateTimeZone('Asia/Manila'));
       $this->longId = str_replace("-","", Uuid::uuid4()->getHex());
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

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

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getInvitedAt(): ?\DateTimeInterface
    {
        return $this->invitedAt;
    }

    public function setInvitedAt(?\DateTimeInterface $invitedAt): self
    {
        $this->invitedAt = $invitedAt;

        return $this;
    }

    public function getUserGroup(): ?Group
    {
        return $this->userGroup;
    }

    public function setUserGroup(?Group $userGroup): self
    {
        $this->userGroup = $userGroup;

        return $this;
    }

    public function getLongId(): ?string
    {
        return $this->longId;
    }

    public function checkExpiration()
    {   
        return $this->status != 'Accepted' && ($this->invitedAt->modify('+30 minutes') < new \DateTime() );
    }
}
