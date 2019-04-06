<?php

namespace App\Domain\Account\DomainEvents;

use Spatie\EventProjector\DomainEvent;

final class MoneySubtracted implements DomainEvent
{
    /** @var int */
    public $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }
}
