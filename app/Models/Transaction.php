<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'is_group',
        'grand_total',
        'pay',
        'charge',
        'payment_method',
        'payment_ref',
        'gate'
    ];

    /**
     * Get the operator that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    /**
     * Get the ticket type that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(TicketType::class, 'type_id');
    }

    /**
     * Get all of the details for the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }

    /**
     * Get all of the tickets for the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, TransactionDetail::class, 'transaction_id', 'transaction_detail_id', 'id', 'id');
    }
}
