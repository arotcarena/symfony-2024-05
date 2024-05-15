<?php
namespace App\LifecycleEventTrait;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;


trait EntityDateTrait
{
    #[ORM\PrePersist]
    public function setCreatedAtValue()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}