<?php

namespace App\Domain\Account\Projectors;

use App\Account;
use App\Domain\Account\Events\AccountCreated;
use App\Domain\Account\Events\AccountDeleted;
use App\Domain\Account\Events\MoneyAdded;
use App\Domain\Account\Events\MoneySubtracted;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class AccountProjector implements Projector
{
    use ProjectsEvents;

    public function onAccountCreated(AccountCreated $event, string $aggregateUuid)
    {
        Account::create([
            'uuid' => $aggregateUuid,
            'name' => $event->name,
            'user_id' => $event->userId,
        ]);
    }

    public function onMoneyAdded(MoneyAdded $event, string $aggregateUuid)
    {
        $account = Account::uuid($aggregateUuid);

        $account->balance += $event->amount;

        $account->save();
    }

    public function onMoneySubtracted(MoneySubtracted $event, string $aggregateUuid)
    {
        $account = Account::uuid($aggregateUuid);

        $account->balance -= $event->amount;

        $account->save();
    }

    public function onAccountDeleted(AccountDeleted $event, string $aggregateUuid)
    {
        Account::uuid($aggregateUuid)->delete();
    }
}
