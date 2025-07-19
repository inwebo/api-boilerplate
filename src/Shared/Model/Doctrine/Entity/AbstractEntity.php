<?php

namespace App\Shared\Model\Doctrine\Entity;

use App\Shared\Model\Interface\TimestampableInterface;
use App\Shared\Model\Interface\UniqueEntityInterface;
use App\Shared\Model\Trait\TimestampableTrait;
use App\Shared\Model\Trait\UniqueEntityTrait;
use Symfony\Component\Uid\Uuid;

class AbstractEntity implements UniqueEntityInterface, TimestampableInterface
{
    use TimestampableTrait;
    use UniqueEntityTrait;

    public function __construct()
    {
        $this->uuid = Uuid::v7();
    }
}
