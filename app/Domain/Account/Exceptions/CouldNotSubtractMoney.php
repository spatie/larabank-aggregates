<?php

namespace App\Domain\Account\Exceptions;

use DomainException;

class CouldNotSubtractMoney extends DomainException
{
    public static function notEnoughFunds(int $amount): self
    {
        return new static("Could not subtract amount {$amount} because you can not go below -5000.");
    }
}
