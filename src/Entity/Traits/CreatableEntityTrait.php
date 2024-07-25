<?php

namespace App\Entity\Traits;

trait CreatableEntityTrait
{
    /**
     * @var DateTimeInterface|null
     */
    protected $createdAt;

    /**
     * Get createdAt value.
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt value.
     */
    public function setCreatedAt(?\DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
