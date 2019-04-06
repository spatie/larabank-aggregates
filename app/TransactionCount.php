<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionCount extends Model
{
    public $guarded = [];

    public static function uuid(string $uuid)
    {
        return static::where('uuid', $uuid)->first();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function incrementCount()
    {
        $this->count += 1;

        $this->save();
    }
}
