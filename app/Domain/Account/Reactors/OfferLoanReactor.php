<?php

namespace App\Domain\Account\Reactors;

use App\Account;
use App\Domain\Account\Events\MoreMoneyNeeded;
use App\Mail\LoanProposalMail;
use Illuminate\Support\Facades\Mail;
use Spatie\EventSourcing\EventHandlers\EventHandler;
use Spatie\EventSourcing\EventHandlers\HandlesEvents;

final class OfferLoanReactor implements EventHandler
{
    use HandlesEvents;

    public function __invoke(MoreMoneyNeeded $event, string $aggregateUuid)
    {
        $account = Account::where('uuid', $aggregateUuid)->first();

        Mail::to($account->user)->send(new LoanProposalMail());
    }
}
