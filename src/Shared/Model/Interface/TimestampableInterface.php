<?php

declare(strict_types=1);

namespace App\Shared\Model\Interface;

interface TimestampableInterface
{
    public function getCreatedAt(): \DateTimeInterface;

    public function getUpdatedAt(): \DateTimeInterface;

    public function getDeletedAt(): ?\DateTimeInterface;
}
