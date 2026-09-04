<?php

namespace App\Entity;

use App\Entity\Cart;
use App\Entity\Trait\CoreTrait;
use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Order
{
    use CoreTrait;

    #[ORM\Column(unique: true)]
    #[Assert\NotBlank]
    private ?string $reference;

    #[ORM\OneToOne(inversedBy: 'cart', targetEntity: Cart::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $cart;

    public function __construct()
    {
        $this->initializeCoreProperties();
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

    public function getCart(): ?Cart
    {
        return $this->order;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getNetAmount(): int
    {
        return 0;
    }

    public function getRawAmount(): int
    {
        return 0;
    }

    public function getTaxAmount(): int
    {
        return 0;
    }
}
