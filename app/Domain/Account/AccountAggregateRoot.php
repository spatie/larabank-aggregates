<?php

namespace App\Domain\Account;

use App\Domain\Account\Events\AccountCreated;
use App\Domain\Account\Events\AccountDeleted;
use App\Domain\Account\Events\AccountLimitHit;
use App\Domain\Account\Events\MoneyAdded;
use App\Domain\Account\Events\MoneySubtracted;
use App\Domain\Account\Events\MoreMoneyNeeded;
use App\Domain\Account\Exceptions\CouldNotSubtractMoney;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class AccountAggregateRoot extends AggregateRoot
{
    protected int $balance = 0;

    protected int $accountLimit = -5000;

    protected int $accountLimitHitInARow = 0;

    public function createAccount(string $name, string $userId)
    {
        $this->recordThat(new AccountCreated($name, $userId));

        return $this;
    }

    public function addMoney(int $amount)
    {
        $this->recordThat(new MoneyAdded($amount));

        return $this;
    }

    public function applyMoneyAdded(MoneyAdded $event)
    {
        ray('applying money added ' . $event->amount);

        $this->accountLimitHitInARow = 0;

        $this->balance += $event->amount;
    }

    public function subtractMoney(int $amount)
    {
        if (!$this->hasSufficientFundsToSubtractAmount($amount)) {
            $this->recordThat(new AccountLimitHit());

            if ($this->needsMoreMoney()) {
                $this->recordThat(new MoreMoneyNeeded());
            }

            $this->persist();

            throw CouldNotSubtractMoney::notEnoughFunds($amount);
        }

        $this->recordThat(new MoneySubtracted($amount));
    }

    public function applyMoneySubtracted(MoneySubtracted $event)
    {
        $this->balance -= $event->amount;

        $this->accountLimitHitInARow = 0;
    }

    public function deleteAccount()
    {
        $this->recordThat(new AccountDeleted());

        return $this;
    }

    public function applyAccountLimitHit()
    {
        $this->accountLimitHitInARow++;
    }

    private function hasSufficientFundsToSubtractAmount(int $amount): bool
    {
        return $this->balance - $amount >= $this->accountLimit;
    }

    private function needsMoreMoney()
    {
        return $this->accountLimitHitInARow >= 3;
    }
}
