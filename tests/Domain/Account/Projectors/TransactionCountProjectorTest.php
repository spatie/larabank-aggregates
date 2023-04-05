<?php

namespace Tests\Domain\Account\Projectors;

use App\Domain\Account\AccountAggregateRoot;
use App\Models\TransactionCount;
use Tests\TestCase;

class TransactionCountProjectorTest extends TestCase
{
    /** @test */
    public function test_transaction_count(): void
    {
        $this->assertDatabaseHas((new TransactionCount())->getTable(), [
            'uuid' => $this->account->uuid,
            'user_id' => $this->user->id,
        ]);

        $transactionCount = TransactionCount::uuid($this->account->uuid);

        $this->assertEquals(0, $transactionCount->count);

        AccountAggregateRoot::retrieve($this->account->uuid)
            ->addMoney(10)
            ->persist();

        $transactionCount->refresh();

        $this->assertEquals(1, $transactionCount->count);
    }
}
