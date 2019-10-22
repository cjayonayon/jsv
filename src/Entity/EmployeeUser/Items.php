<?php

namespace App\Entity\EmployeeUser;

use App\Entity\Admin\Group;
use App\Entity\Sysadmin\Employee;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\CheckItem;
use App\Validator\CheckUploadItem;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeUser\ItemsRepository")
 */
class Items
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @CheckItem()
     * @Assert\Regex(pattern="/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v\=[a-zA-Z0-9\/\*\-\_\?\;\%\=\.]/", message="The link should be a youtube link") 
     * @Assert\NotBlank(message="This should not be blank please paste a youtube link.")
     */
    private $videoId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sysadmin\Employee", inversedBy="employeeItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $playlist;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(
     *      maxSize = "30000000",
     *      mimeTypes={ "video/mp4"},
     *      mimeTypesMessage = "Please upload a valid MP4 file",
     *      maxSizeMessage = "File is too large",
     *      uploadErrorMessage = "File did not upload"
     * )
     * @CheckUploadItem()
     */
    private $uploadFilename;

    /**
     * @ORM\Column(type="datetime")
     */
    private $removedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin\Group", inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $itemGroup;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EmployeeUser\Queue", mappedBy="item")
     */
    private $queues;

    public function __construct()
    {
        $this->status = 'Add';
        $this->removedAt = new \DateTime();
        $this->queues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    public function setVideoId(?string $videoId): self
    {
        $this->videoId = $videoId;

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

    public function getPlaylist(): ?string
    {
        return $this->playlist;
    }

    public function setPlaylist(?string $playlist): self
    {
        $this->playlist = $playlist;

        return $this;
    }

    public function getUploadFilename(): ?string
    {
        return $this->uploadFilename;
    }

    public function setUploadFilename(?string $uploadFilename): self
    {
        $this->uploadFilename = $uploadFilename;

        return $this;
    }

    public function getRemovedAt(): ?\DateTimeInterface
    {
        return $this->removedAt;
    }

    public function setRemovedAt(\DateTimeInterface $removedAt): self
    {
        $this->removedAt = $removedAt;

        return $this;
    }

    public function checkAddedItem(Group $itemGroup)
    {
        return $this->itemGroup == $itemGroup && $this->status == 'Removed' && ($this->removedAt->modify('+2 hours') < new \DateTime());
    }

    public function checkDuplicateItem(Employee $employee)
    {
        return $this->employee == $employee && $this->status == 'Add';
    }

    public function checkDuplicateGroupItem(Group $itemGroup)
    {
        return $this->itemGroup == $itemGroup && $this->status == 'Add';
    }

    public function checkMaxItem()
    {
        return ($this->removedAt->modify('+1 hour') < new \DateTime());
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

    public function getItemGroup(): ?Group
    {
        return $this->itemGroup;
    }

    public function setItemGroup(?Group $itemGroup): self
    {
        $this->itemGroup = $itemGroup;

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
            $queue->setItem($this);
        }

        return $this;
    }

    public function removeQueue(Queue $queue): self
    {
        if ($this->queues->contains($queue)) {
            $this->queues->removeElement($queue);
            // set the owning side to null (unless already changed)
            if ($queue->getItem() === $this) {
                $queue->setItem(null);
            }
        }

        return $this;
    }

}
