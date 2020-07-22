<?php

namespace App\Domain\Account\Reactors;

use App\Account;
use App\Domain\Account\Events\MoreMoneyNeeded;
use App\Mail\LoanProposalMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Spatie\EventSourcing\EventHandlers\EventHandler;
use Spatie\EventSourcing\EventHandlers\HandlesEvents;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

final class OfferLoanReactor extends Reactor implements ShouldQueue
{
    public function __invoke(MoreMoneyNeeded $event, string $aggregateUuid)
    {
        $account = Account::where('uuid', $aggregateUuid)->first();

        Mail::to($account->user)->send(new LoanProposalMail());
    }
}
