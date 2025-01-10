<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'icon_url',
        'units_available',
        'units_per_dose',
        'start_date',
        'end_date',
        'schedule_type',
        'schedule',
        'is_chronic',
        'expiration_date',
        'notify_low_stock',
        'notify_expiration',
    ];

    protected $casts = [
        'schedule' => 'array',
        'is_chronic' => 'boolean',
        'notify_low_stock' => 'boolean',
        'notify_expiration' => 'boolean',
    ];

    /**
     * Check if the stock is running low.
     */
    public function isStockLow(): bool
    {
        return $this->units_available <= 5; // Threshold for low stock
    }
}
