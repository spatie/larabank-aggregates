<?php

namespace App\Domain\Account\DomainEvents;

use Spatie\EventProjector\DomainEvent;

final class AccountCreated implements DomainEvent
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
