<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    public $guarded = [];

    public static function uuid(string $uuid): self
    {
        return static::where('uuid', $uuid)->first();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addMoney(int $amount)
    {
        $this->balance += $amount;

        $this->save();

        return;
    }

    public function subtractMoney(int $amount)
    {
        $this->balance -= $amount;

        $this->save();

        return;
    }
}
