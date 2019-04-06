<?php

namespace App\Domain\Account\Projectors;

use App\Domain\Account\DomainEvents\AccountCreated;
use App\Domain\Account\DomainEvents\AccountDeleted;
use App\Domain\Account\DomainEvents\MoneyAdded;
use App\Domain\Account\DomainEvents\MoneySubtracted;
use App\TransactionCount;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

final class TransactionCountProjector implements Projector
{
    use ProjectsEvents;

    protected $handlesEvents = [
        AccountCreated::class => 'onAccountCreated',
        MoneyAdded::class => 'onMoneyAdded',
        MoneySubtracted::class => 'onMoneySubtracted',
        AccountDeleted::class => 'onAccountDeleted',
    ];

    public function onAccountCreated(AccountCreated $event, string $uuid)
    {
        TransactionCount::create([
            'uuid' => $uuid,
            'user_id' => $event->userId,
        ]);
    }

    public function onMoneyAdded(string $uuid)
    {
        TransactionCount::uuid($uuid)->incrementCount();
    }

    public function onMoneySubtracted(string $uuid)
    {
        TransactionCount::uuid($uuid)->incrementCount();
    }

    public function onAccountDeleted(string $uuid)
    {
        TransactionCount::uuid($uuid)->delete();
    }
}
