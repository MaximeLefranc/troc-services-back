<?php

namespace App\Entity;

use App\Repository\AdvertisementsRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AdvertisementsRepository::class)
 */
class Advertisements
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"advertisements_browse"})
     * 
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"advertisements_browse"})
     * @Groups({"advertisements_read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"advertisements_browse"})
     * @Groups({"advertisements_read"})
     * 
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"advertisements_browse"})
     * @Groups({"advertisements_read"})
     */
    private $approved;

     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"advertisements_browse"})
     * @Groups({"advertisements_read"})
     */
    private $image;

    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime('@'.strtotime('now'));
    }


    /**
     * @ORM\Column(type="datetime")
     * 
     * 
     * 
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * 
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="advertisements", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"category_read"})
     * 
     * 
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Skill::class, inversedBy="advertisements")
     * @Groups({"skill_read"})
     */
    private $skills;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="advertisements", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     *

     * 
     */
    private $user;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
      
     
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function isApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): self
    {
        $this->approved = $approved;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Categories
    {
        return $this->category;
    }

    public function setCategory(?Categories $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills[] = $skill;
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
