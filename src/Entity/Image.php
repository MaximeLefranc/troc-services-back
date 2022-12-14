<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @ORM\Table(name="image")
 * @Vich\Uploadable
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="image_id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="image_name")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100, name="image_mime")
     */
    private $mime;

    /**
     * @Vich\UploadableField(mapping="image", fileNameProperty="target_image")
     *
     * @var File
     */
    private $target_image_file;

    /**
     * @ORM\Column(type="string", length=255, name="target_image")
     *
     * @var string
     */
    private $target_image;

    /**
     * @ORM\Column(type="datetime", name="image_created_at")
     */
    private $created_at;

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

    public function getMime(): ?string
    {
        return $this->mime;
    }

    public function setMime(string $mime): self
    {
        $this->mime = $mime;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile|null $target_image_file
     */
    public function setTargetImageFile(?File $target_image_file = null): void
    {
        $this->target_image_file = $target_image_file;

        if (null !== $target_image_file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getTargetImageFile(): ?File
    {
        return $this->target_image_file;
    }

    public function setTargetImage(?string $target_image): void
    {
        $this->target_image = $target_image;
    }

    public function getTargetImage(): ?string
    {
        return $this->target_image;
    }
}