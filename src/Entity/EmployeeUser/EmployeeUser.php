<?php

namespace App\Entity\EmployeeUser;

use App\Entity\Admin\Group;
use App\Entity\Sysadmin\Employee;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Validator\EmployeePlainPassword;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeUser\EmployeeUserRepository")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class EmployeeUser implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

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
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(message="Password should not be blank.")
     * @Assert\Length(
     *          min = 6,
     *          minMessage = "Password should not be less than 6 characters"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Temporay Password should not be blank.")
     * @EmployeePlainPassword()
     */
    private $plainPassword;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sysadmin\Employee", inversedBy="employeeUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employeeId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin\Group", inversedBy="employeeUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employeeGroup;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return ['ROLE_EMPLOYEE'];
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getEmployeeId(): ?Employee
    {
        return $this->employeeId;
    }

    public function setEmployeeId(?Employee $employeeId): self
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

}
