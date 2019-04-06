<?php

namespace App\Domain\Account\Projectors;

use App\Account;
use App\Domain\Account\DomainEvents\MoreMoneyNeeded;
use App\Mail\LoanProposalMail;
use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;
use Illuminate\Support\Facades\Mail;
use Spatie\EventProjector\EventHandlers\EventHandler;
use Spatie\EventProjector\EventHandlers\HandlesEvents;

final class OfferLoanReactor implements EventHandler
{
    use HandlesEvents;

    protected $handleEvent = MoreMoneyNeeded::class;

    public function __invoke(MoreMoneyNeeded $event, string $uuid)
    {
        $account = Account::where('uuid', $uuid)->first();

        Mail::to($account->user)->send(new LoanProposalMail());
    }
}
