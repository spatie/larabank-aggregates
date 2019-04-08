<?php

namespace App\Domain\Account\Projectors;

use App\Account;
use App\Domain\Account\Events\MoreMoneyNeeded;
use App\Mail\LoanProposalMail;
use Illuminate\Support\Facades\Mail;
use Spatie\EventProjector\EventHandlers\EventHandler;
use Spatie\EventProjector\EventHandlers\HandlesEvents;

final class OfferLoanReactor implements EventHandler
{
    use HandlesEvents;

    protected $handleEvent = MoreMoneyNeeded::class;

    public function __invoke(MoreMoneyNeeded $event, string $aggregateUuid)
    {
        $account = Account::where('uuid', $aggregateUuid)->first();

        Mail::to($account->user)->send(new LoanProposalMail());
    }
}
