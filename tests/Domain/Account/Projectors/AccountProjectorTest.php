<?php

namespace Tests\Domain\Account\Projectors;

use App\Domain\Account\AccountAggregateRoot;
use App\Models\Account;
use Tests\TestCase;

class AccountProjectorTest extends TestCase
{
    /** @test */
    public function test_create(): void
    {
        $this->assertDatabaseHas((new Account())->getTable(), [
            'user_id' => $this->user->id,
            'uuid' => $this->account->uuid,
        ]);

        $this->assertTrue($this->account->user->is($this->user));
    }

    /** @test */
    public function test_add_money(): void
    {
        $this->assertEquals(0, $this->account->balance);

        AccountAggregateRoot::retrieve($this->account->uuid)
            ->addMoney(10)
            ->persist();

        $this->account->refresh();

        $this->assertEquals(10, $this->account->balance);
    }

    /** @test */
    public function test_subtract_money(): void
    {
        $this->assertEquals(0, $this->account->balance);

        $aggregateRoot = AccountAggregateRoot::retrieve($this->account->uuid);
        $aggregateRoot->subtractMoney(10);
        $aggregateRoot->persist();

        $this->account->refresh();

        $this->assertEquals(-10, $this->account->balance);
    }

    /** @test */
    public function test_delete_account(): void
    {
        AccountAggregateRoot::retrieve($this->account->uuid)
            ->deleteAccount()
            ->persist();

        $this->assertDatabaseMissing((new Account())->getTable(), [
            'user_id' => $this->user->id,
            'uuid' => $this->account->uuid,
        ]);
    }
}
