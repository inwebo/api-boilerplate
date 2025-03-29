<?php

declare(strict_types=1);

namespace App\Shared\Model\Trait;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
trait TimestampableTrait
{
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    protected \DateTimeInterface $createdAt;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    protected \DateTimeInterface $updatedAt;

    #[ORM\Column(name: 'deleted_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?\DateTimeInterface $deletedAt = null;

    #[SerializedName('createdAt')]
    public function getCreatedAtTimestamp(): ?int
    {
        return $this->createdAt->getTimestamp() * 1000;
    }

    #[SerializedName('updatedAt')]
    public function getUpdatedAtTimestamp(): ?int
    {
        return $this->updatedAt->getTimestamp() * 1000;
    }

    #[SerializedName('deletedAt')]
    public function getDeletedAtTimestamp(): ?int
    {
        return $this->deletedAt?->getTimestamp() * 1000;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
