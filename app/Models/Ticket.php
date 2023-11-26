<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = [
        'transaction_detail_id',
        'entrance_max',
        'entrance_count',
        'entrance_gate',
        'entrance_door',
        'expires_at'
    ];

    protected $appends = [
        'ticket_type_name',
        'ticket_price'
    ];

    /**
     * Get the transaction that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transactionDetail(): BelongsTo
    {
        return $this->belongsTo(TransactionDetail::class, 'transaction_detail_id');
    }

    protected function getTicketTypeNameAttribute(): string
    {
        return $this->transactionDetail->ticket_type_name;
    }

    protected function getTicketPriceAttribute(): string
    {
        return $this->transactionDetail->price;
    }
}
