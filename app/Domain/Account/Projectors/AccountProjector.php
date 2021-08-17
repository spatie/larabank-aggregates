<?php

namespace App\Domain\Account\Projectors;

use App\Models\Account;
use App\Domain\Account\Events\AccountCreated;
use App\Domain\Account\Events\AccountDeleted;
use App\Domain\Account\Events\MoneyAdded;
use App\Domain\Account\Events\MoneySubtracted;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class AccountProjector extends Projector
{
    public function onAccountCreated(AccountCreated $event)
    {
        Account::create([
            'uuid' => $event->aggregateRootUuid(),
            'name' => $event->name,
            'user_id' => $event->userId,
        ]);
    }

    public function onMoneyAdded(MoneyAdded $event)
    {
        $account = Account::uuid($event->aggregateRootUuid());

        $account->balance += $event->amount;

        $account->save();
    }

    public function onMoneySubtracted(MoneySubtracted $event)
    {
        $account = Account::uuid($event->aggregateRootUuid());

        $account->balance -= $event->amount;

        $account->save();
    }

    public function onAccountDeleted(AccountDeleted $event)
    {
        Account::uuid($event->aggregateRootUuid())->delete();
    }
}
