<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=SkillRepository::class)
 */
class Skill
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"skill_browse"})
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"skill_browse"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Advertisements::class, mappedBy="skills")
     * @Groups({"advertisements_browse"}) // ajouter ce groupe dans le tableau des groupes du controller skillcontroller 
     * 
     */
    private $advertisements;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="skills")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="skill")
     * 
     */
    private $users;

    public function __construct()
    {
        $this->advertisements = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Advertisements>
     */
    public function getAdvertisements(): Collection
    {
        return $this->advertisements;
    }

    public function addAdvertisement(Advertisements $advertisement): self
    {
        if (!$this->advertisements->contains($advertisement)) {
            $this->advertisements[] = $advertisement;
            $advertisement->addSkill($this);
        }

        return $this;
    }

    public function removeAdvertisement(Advertisements $advertisement): self
    {
        if ($this->advertisements->removeElement($advertisement)) {
            $advertisement->removeSkill($this);
        }

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
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addSkill($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeSkill($this);
        }

        return $this;
    }
}
