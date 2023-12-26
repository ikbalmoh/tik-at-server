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

    protected $appends = ['price', 'can_delete'];

    protected function getPriceAttribute(): float | null
    {
        $weekDay = date('w', time());
        return ($weekDay == 0 || $weekDay == 6) ? $this->holiday_price : $this->regular_price;
    }

    protected function getCanDeleteAttribute(): bool
    {
        return $this->id > 3;
    }
}
