<?php

namespace App\Entity;

use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\Trait\CoreTrait;
use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Cart
{
    use CoreTrait;

    #[ORM\OneToOne(mappedBy: 'order', targetEntity: Order::class)]
    private ?Order $order;

    #[ORM\OneToMany(targetEntity: CartItem::class, mappedBy: 'cart', cascade: ['persist', 'remove'])]
    private Collection $cartItems;

    public function __construct()
    {
        $this->initializeCoreProperties();

        $this->cartItems = new ArrayCollection();
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function setCartItems(array $cartItems): self
    {
        $this->cartItem = new ArrayCollection();

        foreach ($cartItems as $cartItem) {
            $this->addCartItem($cartItem);
        }

        return $this;
    }

    public function addCartItem(CartItem $cartItem): self
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems[] = $cartItem;
            $cartItem->setCart($this);
        }

        return this;
    }

    public function removeCartItem(CartItem $cartItem): self
    {
        if ($this->cartItems->contains($cartItem)) {
            $this->cartItems->removeElement($cartItem);
        }

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
