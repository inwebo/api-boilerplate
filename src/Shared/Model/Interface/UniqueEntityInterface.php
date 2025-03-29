<?php

declare(strict_types=1);

namespace App\Shared\Model\Interface;

use Symfony\Component\Uid\Uuid;

interface UniqueEntityInterface
{
    public function getId(): ?int;

    public function getUuid(): Uuid;
}
