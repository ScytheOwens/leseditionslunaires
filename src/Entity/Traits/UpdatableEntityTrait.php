<?php

namespace App\Entity\Traits;

trait UpdatableEntityTrait
{
    /**
     * @var DateTimeInterface|null
     */
    protected $updatedAt;

    /**
     * Get updatedAt value.
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedAt value.
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
