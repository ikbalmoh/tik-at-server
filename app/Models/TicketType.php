<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
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

    protected function getPriceAttribute(): float|null
    {
        $weekDay = date('w', time());
        return ($weekDay == 0 || $weekDay == 6) ? $this->holiday_price : $this->regular_price;
    }

    protected function getCanDeleteAttribute(): bool
    {
        return true;
    }

    protected function forReport(): Collection
    {
        return $this->where('is_active', true)->where('regular_price', '>', 0)->where('holiday_price', '>', 0)->get();
    }
}
