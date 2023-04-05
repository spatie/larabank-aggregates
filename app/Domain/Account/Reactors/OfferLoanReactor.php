<?php

namespace App\Domain\Account\Reactors;

use App\Domain\Account\Events\MoreMoneyNeeded;
use App\Mail\LoanProposalMail;
use App\Models\Account;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class OfferLoanReactor extends Reactor implements ShouldQueue
{
    public function __invoke(MoreMoneyNeeded $event)
    {
        $account = Account::where('uuid', $event->aggregateRootUuid())->first();

        Mail::to($account->user)->send(new LoanProposalMail());
    }
}
