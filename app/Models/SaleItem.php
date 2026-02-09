<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'item_id',
        'qty',
        'unit_price',
        'line_total',
        'assigned_user_id',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function assignedStaff(): BelongsTo
    {
        // optional staff member tied to the line item (salon commissions later)
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
