<?php

namespace Tests;

use App\Domain\Account\AccountAggregateRoot;
use App\Domain\Account\Events\AccountCreated;
use App\Domain\Account\Events\AccountDeleted;
use App\Domain\Account\Events\AccountLimitHit;
use App\Domain\Account\Events\MoneyAdded;
use App\Domain\Account\Events\MoneySubtracted;
use App\Domain\Account\Events\MoreMoneyNeeded;
use App\Domain\Account\Exceptions\CouldNotSubtractMoney;

class AccountAggregateRootTest extends TestCase
{
    private const ACCOUNT_UUID = 'accounts-uuid';

    private const ACCOUNT_NAME = 'fake-account';

    /** @test */
    public function can_create(): void
    {
        AccountAggregateRoot::fake(self::ACCOUNT_UUID)
            ->given([])
            ->when(function (AccountAggregateRoot $accountAggregateRoot): void {
                $accountAggregateRoot->createAccount(self::ACCOUNT_NAME, $this->user->id);
            })
            ->assertRecorded([
                new AccountCreated(self::ACCOUNT_NAME, $this->user->id)
            ]);
    }

    /** @test */
    public function can_add_money(): void
    {
        AccountAggregateRoot::fake(self::ACCOUNT_UUID)
            ->given([new AccountCreated(self::ACCOUNT_NAME, $this->user->id)])
            ->when(function (AccountAggregateRoot $accountAggregateRoot): void {
                $accountAggregateRoot->addMoney(10);
            })
            ->assertRecorded([
                new MoneyAdded(10)
            ]);
    }

    /** @test */
    public function can_subtract_money(): void
    {
        AccountAggregateRoot::fake(self::ACCOUNT_UUID)
            ->given([
                new AccountCreated(self::ACCOUNT_NAME, $this->user->id),
                new MoneyAdded(10)
            ])
            ->when(function (AccountAggregateRoot $accountAggregateRoot): void {
                $accountAggregateRoot->subtractMoney(10);
            })
            ->assertRecorded([
                new MoneySubtracted(10),
            ])
            ->assertNotRecorded(AccountLimitHit::class);
    }

    /** @test */
    public function cannot_subtract_money_when_money_below_account_limit(): void
    {
        AccountAggregateRoot::fake(self::ACCOUNT_UUID)
            ->given([
                new AccountCreated(self::ACCOUNT_NAME, $this->user->id),
                new MoneySubtracted(5000)
            ])
            ->when(function (AccountAggregateRoot $accountAggregateRoot): void {
                $this->assertExceptionThrown(function () use ($accountAggregateRoot) {
                    $accountAggregateRoot->subtractMoney(1);

                }, CouldNotSubtractMoney::class);
            })
            ->assertApplied([
                new AccountCreated(self::ACCOUNT_NAME, $this->user->id),
                new MoneySubtracted(5000),
                new AccountLimitHit()
            ])
            ->assertNotRecorded(MoneySubtracted::class);

    }

    /** @test */
    public function record_need_more_money_when_limit_hit_equal_three_times(): void
    {
        AccountAggregateRoot::fake(self::ACCOUNT_UUID)
            ->given([
                new AccountCreated(self::ACCOUNT_NAME, $this->user->id),
                new MoneySubtracted(5000),
            ])
            ->when(function (AccountAggregateRoot $accountAggregateRoot): void {
                $this->assertExceptionThrown(function () use ($accountAggregateRoot) {
                    $accountAggregateRoot->subtractMoney(1);
                }, CouldNotSubtractMoney::class);

                $this->assertExceptionThrown(function () use ($accountAggregateRoot) {
                    $accountAggregateRoot->subtractMoney(1);
                }, CouldNotSubtractMoney::class);

                $this->assertExceptionThrown(function () use ($accountAggregateRoot) {
                    $accountAggregateRoot->subtractMoney(1);
                }, CouldNotSubtractMoney::class);

            })->assertApplied([
                new AccountCreated(self::ACCOUNT_NAME, $this->user->id),
                new MoneySubtracted(5000),
                new AccountLimitHit(),
                new AccountLimitHit(),
                new AccountLimitHit(),
                new MoreMoneyNeeded(),
            ]);
    }

    /** @test */
    public function can_delete_account(): void
    {
        AccountAggregateRoot::fake(self::ACCOUNT_UUID)
            ->given([new AccountCreated(self::ACCOUNT_NAME, $this->user->id)])
            ->when(function (AccountAggregateRoot $accountAggregateRoot): void {
                $accountAggregateRoot->deleteAccount();
            })
            ->assertRecorded([
                new AccountDeleted()
            ]);
    }

}
