<?php

namespace App\Entity;

use App\Repository\MessagesRepository;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Proxies\__CG__\App\Entity\User as EntityUser;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MessagesRepository::class)
 */
class Messages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"message_browse"})
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"message_browse"})
     * 
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull
     * @Groups({"message_browse"})
     * 
     */
    private $sentAt;



    /**
     * @ORM\Column(type="boolean")
     * @Groups({"message_browse"})
     * 
     */
    private $isRead = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"message_browse"})
     * 
     */
    private $isHidden = false;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sender")
     * @Groups({"message_browse"})
     *
     * 
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="receiver")
     * @Groups({"message_browse"})
     * 
     * 
     * 
     */
    private $receiver;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"message_browse"})
     */
    private $object;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    

    public function __construct()
    {

      
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }


    public function isIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function isIsHidden(): ?bool
    {
        return $this->isHidden;
    }

    public function setIsHidden(bool $isHidden): self
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(string $object): self
    {
        $this->object = $object;

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

   
}
