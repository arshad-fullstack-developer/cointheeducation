<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'stationary_item_id',
        'godown_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'company',
        'salesperson',
        'payment_type',
        'procurement_date',
        'invoice_number',
        'notes',
        'status',
    ];

    protected $casts = [
        'procurement_date' => 'date',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    // Relationships
    public function stationaryItem()
    {
        return $this->belongsTo(StationaryItem::class);
    }

    public function godown()
    {
        return $this->belongsTo(Godown::class);
    }

    // Scopes
    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('procurement_date', [$startDate, $endDate]);
    }

    public function scopeByPaymentType($query, $paymentType)
    {
        return $query->where('payment_type', $paymentType);
    }

    public function scopeByCompany($query, $company)
    {
        return $query->where('company', $company);
    }

    // Methods
    public function isReceived()
    {
        return $this->status === 'received';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function getDaysSinceProcurementAttribute()
    {
        return $this->procurement_date->diffInDays(now());
    }
}
