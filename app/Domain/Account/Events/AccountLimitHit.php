<?php

namespace App\Domain\Account\Events;

use Spatie\EventSourcing\ShouldBeStored;

final class AccountLimitHit implements ShouldBeStored
{
}
