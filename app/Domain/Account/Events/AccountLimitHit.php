<?php

namespace App\Domain\Account\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

final class AccountLimitHit extends ShouldBeStored
{
}
