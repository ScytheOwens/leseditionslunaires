<?php

namespace App\Entity;

use App\Entity\Category;
use App\Entity\ProductVariant;
use App\Entity\Trait\CoreTrait;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Product
{
    use CoreTrait;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $name;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $description;

    #[ORM\Column(unique: true)]
    #[Assert\NotBlank]
    private ?string $slug;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank]
    private ?\DateTimeImmutable $releasedOn;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    private Collection $categories;

    #[ORM\OneToMany(targetEntity: ProductVariant::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
    private Collection $productVariants;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getReleasedOn(): ?\DateTimeImmutable
    {
        return $this->releasedOn;
    }

    public function setReleasedOn(?\DateTimeImmutable $releasedOn): self
    {
        $this->releasedOn = $releasedOn;

        return $this;
    }

    public function getCategories(): Collection
    {
        return $this->category;
    }

    public function setCategories(array $categories): self
    {
        $this->categories = new ArrayCollection();

        foreach($categories as $category) {
            $this->addCategory($category);
        }

        return $this;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addProduct($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function getProductVariants(): Collection
    {
        return $this->productVariants;
    }

    public function setProductVariants(array $productVariants): self
    {
        $this->productVariants = new ArrayCollection();

        foreach($productVariants as $productVariant) {
            $this->addProductVariant($productVariant);
        }

        return $this;
    }

    public function addProductVariant(ProductVariant $productVariant): self
    {
        if (!$this->productVariants->contains($productVariant)) {
            $this->productVariants[] = $productVariant;
            $productVariant->setProduct($this);
        }

        return $this;
    }

    public function removeProductVariant(ProductVariant $productVariant): self
    {
        if ($this->productVariants->contains($productVariant)) {
            $this->productVariants->removeElement($productVariant);
        }

        return $this;
    }

    public function getMainVariant(): ?ProductVariant
    {
        foreach ($this->productVariants as $productVariant) {
            if ($productVariant->isMainVariant()) {
                return $productVariant;
            }
        }

        return null;
    }
}
