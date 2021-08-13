<?php

namespace Tests;

use App\Domain\Account\AccountAggregateRoot;
use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;
use Throwable;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected User $user;

    protected Account $account;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->account = $this->createAccount();
    }

    protected function assertExceptionThrown(callable $callable, string $expectedExceptionClass): void
    {
        try {
            $callable();

            $this->assertTrue(false, "Expected exception `{$expectedExceptionClass}` was not thrown.");
        } catch (Throwable $exception) {
            if (! $exception instanceof $expectedExceptionClass) {
                throw $exception;
            }
            $this->assertInstanceOf($expectedExceptionClass, $exception);
        }
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
