<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Ticket extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = [
        'uid',
        'transaction_id',
        'entrance_max',
        'entrance_count',
        'expired_at'
    ];

    /**
     * Get the transaction that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
