<?php

namespace App\Domain\Account\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

final class MoneyAdded extends ShouldBeStored
{
    /** @var int */
    public $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }
}
