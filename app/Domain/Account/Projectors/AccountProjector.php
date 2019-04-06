<?php

namespace App\Domain\Account\Projectors;

use App\Account;
use App\Domain\Account\DomainEvents\AccountCreated;
use App\Domain\Account\DomainEvents\AccountDeleted;
use App\Domain\Account\DomainEvents\MoneyAdded;
use App\Domain\Account\DomainEvents\MoneySubtracted;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

final class AccountProjector implements Projector
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
        Account::create([
            'uuid' => $uuid,
            'name' => $event->name,
            'user_id' => $event->userId,
        ]);
    }

    public function onMoneyAdded(MoneyAdded $event, string $uuid)
    {
        $account = Account::uuid($uuid);

        $account->balance += $event->amount;

        $account->save();
    }

    public function onMoneySubtracted(MoneySubtracted $event, string $uuid)
    {
        $account = Account::uuid($uuid);

        $account->balance -= $event->amount;

        $account->save();
    }

    public function onAccountDeleted(AccountDeleted $event, string $uuid)
    {
        Account::uuid($uuid)->delete();
    }
}
