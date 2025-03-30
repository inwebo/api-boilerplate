<?php

declare(strict_types=1);

namespace App\Shared\Model\Trait;

use Doctrine\Common\Collections\Collection;

trait StatesTrait
{
    public function getEventSourcingStates(): Collection
    {
        return $this->states;
    }
}
