<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'regular_price',
        'holiday_price',
        'is_active'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = ['price'];

    protected function getPriceAttribute(): float
    {
        return $this->regular_price;
    }
}
