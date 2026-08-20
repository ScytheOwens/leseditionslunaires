<?php

namespace App\Entity;

use App\Entity\Trait\CoreTrait;
use App\Repository\MediumRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediumRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Medium
{
    use CoreTrait;

    public const MIME_TYPE_TYPE_APPLICATION = 'application';
    public const MIME_TYPE_TYPE_AUDIO = 'audio';
    public const MIME_TYPE_TYPE_IMAGE = 'image';
    public const MIME_TYPE_TYPE_TEXT = 'text';
    public const MIME_TYPE_TYPE_VIDEO = 'video';

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $name;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $mimeType;

    #[ORM\Column]
    private ?int $size;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $url;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $tag;

    public function __construct()
    {
        $this->initializeCoreProperties();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType();
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }
}