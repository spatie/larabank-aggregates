<?php

namespace App\Domain\Account\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

final class MoneySubtracted extends ShouldBeStored
{
    /** @var int */
    public $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }
}
