<?php

namespace App\Domain\Account\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

final class AccountCreated extends ShouldBeStored
{
    /** @var string */
    public $name;

    /** @var int */
    public $userId;

    public function __construct(string $name, int $userId)
    {
        $this->name = $name;

        $this->userId = $userId;
    }
}
