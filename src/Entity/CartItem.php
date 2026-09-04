<?php

namespace App\Entity;

use App\Entity\Trait\CoreTrait;
use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CartItem
{
    use CoreTrait;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $name;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $reference;

    #[ORM\Column]
    private ?int $rawPrice;

    #[ORM\Column]
    private ?float $taxRate;

    #[ORM\Column(nullable: true)]
    private ?int $weight;

    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'cartItems')]
    private ?Cart $cart;

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

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }
}
