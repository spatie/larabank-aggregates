<?php

namespace Tests\Domain\Account\Reactors;

use App\Domain\Account\AccountAggregateRoot;
use App\Domain\Account\Exceptions\CouldNotSubtractMoney;
use App\Mail\LoanProposalMail;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OfferLoanReactorTest extends TestCase
{
    /** @test */
    public function test_send_offer_loan(): void
    {
        Mail::fake();

        $aggregate = AccountAggregateRoot::retrieve($this->account->uuid);

        $aggregate->subtractMoney(5000);
        $aggregate->persist();

        $this->assertExceptionThrown(function () use ($aggregate) {
            $aggregate->subtractMoney(1);
        }, CouldNotSubtractMoney::class);

        $this->assertExceptionThrown(function () use ($aggregate) {
            $aggregate->subtractMoney(1);
        }, CouldNotSubtractMoney::class);

        Mail::assertNotSent(LoanProposalMail::class);

        $this->assertExceptionThrown(function () use ($aggregate) {
            $aggregate->subtractMoney(1);
        }, CouldNotSubtractMoney::class);

        Mail::assertSent(function (LoanProposalMail $mail) {
            return $mail->hasTo($this->user->email);
        });
    }
}
