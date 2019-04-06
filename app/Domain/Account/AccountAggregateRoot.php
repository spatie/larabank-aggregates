<?php

namespace App\Domain\Account;

use App\Domain\Account\DomainEvents\AccountCreated;
use App\Domain\Account\DomainEvents\AccountDeleted;
use App\Domain\Account\DomainEvents\AccountLimitHit;
use App\Domain\Account\DomainEvents\MoneyAdded;
use App\Domain\Account\DomainEvents\MoneySubtracted;
use App\Domain\Account\DomainEvents\MoreMoneyNeeded;
use App\Domain\Account\Exceptions\CouldNotSubtractMoney;
use App\Domain\Account\Projectors\AccountProjector;
use App\Domain\Account\Projectors\OfferLoanReactor;
use App\Domain\Account\Projectors\TransactionCountProjector;
use Spatie\EventProjector\AggregateRoot;
use Spatie\LaravelEventSauce\Concerns\IgnoresMissingMethods;

final class AccountAggregateRoot extends AggregateRoot
{
    protected $projectors = [
        AccountProjector::class,
        TransactionCountProjector::class,
    ];

    protected $reactors = [
        OfferLoanReactor::class,
    ];

    /** @var int */
    private $balance = 0;

    /** @var int */
    private $accountLimit = -5000;

    /** @var int */
    private $accountLimitHitInARow = 0;

    public function createAccount(string $name, string $userId)
    {
        $this->recordThat(new AccountCreated($name, $userId));

        return $this;
    }

    public function deleteAccount()
    {
        $this->recordThat(new AccountDeleted());

        return $this;
    }

    public function addMoney(int $amount)
    {
        $this->recordThat(new MoneyAdded($amount));

        return $this;
    }

    protected function applyMoneyAdded(MoneyAdded $event)
    {
        // \Log::info('apply money subtracted' . $event->amount);

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

    protected function applyMoneySubtracted(MoneySubtracted $event)
    {
        $this->balance -= $event->amount;

        $this->accountLimitHitInARow = 0;
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
