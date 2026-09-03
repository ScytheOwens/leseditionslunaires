<?php

namespace App\Entity;

use App\Entity\Medium;
use App\Entity\Product;
use App\Entity\Trait\CoreTrait;
use App\Repository\ProductVariantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductVariantRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ProductVariant
{
    use CoreTrait;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $name;

    #[ORM\Column(unique: true)]
    #[Assert\NotBlank]
    private ?string $reference;

    #[ORM\Column]
    private ?int $rawPrice;

    #[ORM\Column]
    private ?float $taxRate;

    #[ORM\Column(nullable: true)]
    private ?int $length;

    #[ORM\Column(nullable: true)]
    private ?int $width;

    #[ORM\Column(nullable: true)]
    private ?int $height;

    #[ORM\Column(nullable: true)]
    private ?int $weight;

    #[ORM\Column]
    private bool $mainVariant = false;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productVariants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product;

    #[ORM\OneToMany(targetEntity: Medium::class, mappedBy: 'productVariant', cascade: ['persist', 'remove'])]
    private Collection $media;

    public function __construct()
    {
        $this->initializeCoreProperties();

        $this->media = new ArrayCollection();
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getRawPrice(): ?int
    {
        return $this->rawPrice;
    }

    public function setRawPrice(?int $rawPrice): self
    {
        $this->rawPrice = $rawPrice;

        return $this;
    }

    public function getTaxRate(): ?float
    {
        return $this->taxRate;
    }

    public function setTaxRate(?float $taxRate): self
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(?int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function isMainVariant(): bool
    {
        return $this->mainVariant;
    }

    public function setMainVariant(bool $mainVariant): self
    {
        $this->mainVariant = $mainVariant;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function setMedia(array $media): self
    {
        $this->media = new ArrayCollection();

        foreach ($media as $medium) {
            $this->addMedium($medium);
        }

        return $this;
    }

    public function addMedium(Medium $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setProductVariant($this);
        }

        return $this;
    }

    public function removeMedium(Medium $medium): self
    {
        if ($this->media->contains($medium)) {
            $this->media->removeElement($medium);
        }

        return $this;
    }

    public function computePrice(): float
    {
        if (empty($this->rawPrice)) {
            return 0;
        }

        if (empty($this->taxRate)) {
            return $this->rawPrice;
        }

        return $this->rawPrice * (1 + $this->taxRate/100);
    }
}
