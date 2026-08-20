<?php

namespace App\Entity;

use App\Entity\Trait\CoreTrait;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Category
{
    use CoreTrait;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $name;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $description;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $slug;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'category', cascade: ['persist'])]
    private Collection $products;

    public function __construct()
    {
        $this->initializeCoreProperties();

        $this->products = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function setProducts(array $products): self
    {
        $this->products = new ArrayCollection();

        foreach ($products as $product) {
            $this->addProduct($product);
        }

        return $this;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
    }
}
