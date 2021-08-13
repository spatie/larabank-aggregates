<?php

namespace Tests\Domain\Account\Projectors;

use App\Domain\Account\AccountAggregateRoot;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class AccountProjector extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function test_create(): void
    {
        $account = $this->createAccount();

        $this->assertDatabaseHas((new Account())->getTable(), [
            'user_id' => $this->user->id,
            'uuid' => $account->uuid,
        ]);

        $this->assertTrue($account->user->is($this->user));
    }

    /** @test */
    public function test_add_money(): void
    {
       $account = $this->createAccount();

        $this->assertEquals(0, $account->balance);

        AccountAggregateRoot::retrieve($account->uuid)
            ->addMoney(10)
            ->persist();

        $account->refresh();

        $this->assertEquals(10, $account->balance);
    }

    /** @test */
    public function test_subtract_money(): void
    {
        $account = $this->createAccount();

        $this->assertEquals(0, $account->balance);

        $aggregateRoot = AccountAggregateRoot::retrieve($account->uuid);
        $aggregateRoot->subtractMoney(10);
        $aggregateRoot->persist();

        $account->refresh();

        $this->assertEquals(-10, $account->balance);
    }

    /** @test */
    public function test_delete_account(): void
    {
        $account = $this->createAccount();

        AccountAggregateRoot::retrieve($account->uuid)
            ->deleteAccount()
            ->persist();

        $this->assertDatabaseMissing((new Account())->getTable(), [
            'user_id' => $this->user->id,
            'uuid' => $account->uuid,
        ]);
    }

    protected function createAccount(): Account
    {
        $uuid = Str::uuid();

        $aggregate = AccountAggregateRoot::retrieve($uuid)
            ->createAccount('account', $this->user->id)
            ->persist();

        return Account::uuid($aggregate->uuid());
    }
}
