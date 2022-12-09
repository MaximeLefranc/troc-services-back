<?php

namespace App\Entity;

use App\Repository\AdvertisementsRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Fresh\VichUploaderSerializationBundle\Annotation as Fresh;
use JMS\Serializer\Annotation as JMS;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=AdvertisementsRepository::class)
 * @Vich\Uploadable
 * @Fresh\VichSerializableClass
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

     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"advertisements_browse"})
     * 
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"advertisements_browse"})
  
     */
    private $approved = false;

      /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @Groups({"advertisements_browse"})
     * 
     * @var File
     *
     * @JMS\Exclude
     *
     * @Vich\UploadableField(mapping="picture", fileNameProperty="imageName")
     *
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string")
     * @Groups({"advertisements_browse"})
     * 
     * @JMS\Expose
     * @JMS\SerializedName("photo")
     * 
     * @Fresh\VichSerializableField("imageFile")
     * 
     */
    private $imageName;

   

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"advertisements_browse"})
     */
    private $isHidden = false;


    /**
     * @ORM\Column(type="datetime")
     * @Groups({"advertisements_browse"})
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
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="advertisements" )
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"category_browse"})
     * 
     * 
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Skill::class, inversedBy="advertisements")
     * @Groups({"skill_browse"})
     */
    private $skills;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="advertisements" )
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_browse"})
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

 /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
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


    public function isHidden(): ?bool
    {
        return $this->isHidden;
    }

    public function setIsHidden(bool $isHidden): self
    {
        $this->isHidden = $isHidden;

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
