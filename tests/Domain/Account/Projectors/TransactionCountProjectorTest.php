<?php

namespace Tests\Domain\Account\Projectors;

use App\Domain\Account\AccountAggregateRoot;
use App\Models\TransactionCount;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class TransactionCountProjectorTest extends TestCase
{
    /** @test */
    public function test_transaction_count(): void
    {
        $uuid = Str::uuid();

        $user = User::factory()->create();

        $aggregate = AccountAggregateRoot::retrieve($uuid)
            ->createAccount('account', $user->id)
            ->persist();

        $this->assertDatabaseHas((new TransactionCount())->getTable(), [
            'uuid' => $aggregate->uuid(),
            'user_id' => $user->id,
        ]);

        $transactionCount = TransactionCount::uuid($uuid);

        $this->assertEquals(0, $transactionCount->count);

        AccountAggregateRoot::retrieve($uuid)
            ->addMoney(10)
            ->persist();

        $transactionCount->refresh();

        $this->assertEquals(1, $transactionCount->count);
    }
}
