<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'stationary_item_id',
        'godown_id',
        'quantity',
        'unit_price',
        'total_amount',
        'profit_amount',
        'donation_amount',
        'salesperson',
        'payment_type',
        'sale_date',
        'project_details',
        'public_message',
        'status',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'profit_amount' => 'decimal:2',
        'donation_amount' => 'decimal:2',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function stationaryItem()
    {
        return $this->belongsTo(StationaryItem::class);
    }

    public function godown()
    {
        return $this->belongsTo(Godown::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('sale_date', [$startDate, $endDate]);
    }

    public function scopeByPaymentType($query, $paymentType)
    {
        return $query->where('payment_type', $paymentType);
    }

    public function scopeBySalesperson($query, $salesperson)
    {
        return $query->where('salesperson', $salesperson);
    }

    // Methods
    public function getProfitPercentageAttribute()
    {
        if ($this->total_amount > 0) {
            return ($this->profit_amount / $this->total_amount) * 100;
        }
        return 0;
    }

    public function getDonationPercentageAttribute()
    {
        if ($this->total_amount > 0) {
            return ($this->donation_amount / $this->total_amount) * 100;
        }
        return 0;
    }

    public function getNetAmountAttribute()
    {
        return $this->total_amount - $this->donation_amount;
    }
}
