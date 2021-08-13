<?php

namespace Tests\Domain\Account\Projectors;

use App\Domain\Account\AccountAggregateRoot;
use App\Domain\Account\Exceptions\CouldNotSubtractMoney;
use App\Mail\LoanProposalMail;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class OfferLoanReactorTest extends TestCase
{
    /** @test */
    public function test_send_offer_loan(): void
    {
        Mail::fake();

        $uuid = Str::uuid();

        $user = User::factory()->create();

        $aggregate = AccountAggregateRoot::retrieve($uuid)
            ->createAccount('account', $user->id)
            ->persist();

        $aggregate->subtractMoney(5000);
        $aggregate->persist();

        $this->assertExceptionThrown(function () use ($aggregate){
            $aggregate->subtractMoney(1);
        }, CouldNotSubtractMoney::class);

        $this->assertExceptionThrown(function () use ($aggregate){
            $aggregate->subtractMoney(1);
        }, CouldNotSubtractMoney::class);

        Mail::assertNotSent(LoanProposalMail::class);

        $this->assertExceptionThrown(function () use ($aggregate){
            $aggregate->subtractMoney(1);
        }, CouldNotSubtractMoney::class);

        Mail::assertSent(LoanProposalMail::class);
    }
}
