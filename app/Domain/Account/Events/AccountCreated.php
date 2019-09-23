<?php

namespace App\Domain\Account\Events;

use Spatie\EventSourcing\ShouldBeStored;

final class AccountCreated implements ShouldBeStored
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
