<?php

namespace App\Domain\Account\Projectors;

use App\Domain\Account\Events\AccountCreated;
use App\Domain\Account\Events\AccountDeleted;
use App\Domain\Account\Events\MoneyAdded;
use App\Domain\Account\Events\MoneySubtracted;
use App\Models\TransactionCount;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class TransactionCountProjector extends Projector
{
    public function onAccountCreated(AccountCreated $event)
    {
        TransactionCount::create([
            'uuid' => $event->aggregateRootUuid(),
            'user_id' => $event->userId,
        ]);
    }

    public function onMoneyAdded(MoneyAdded $event)
    {
        TransactionCount::uuid($event->aggregateRootUuid())->incrementCount();
    }

    public function onMoneySubtracted(MoneySubtracted $event)
    {
        TransactionCount::uuid($event->aggregateRootUuid())->incrementCount();
    }

    public function onAccountDeleted(AccountDeleted $event)
    {
        TransactionCount::uuid($event->aggregateRootUuid())->delete();
    }
}
