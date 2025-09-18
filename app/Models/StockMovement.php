<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'stationary_item_id',
        'from_godown_id',
        'to_godown_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'movement_type',
        'moved_by',
        'movement_date',
        'reason',
        'status',
    ];

    protected $casts = [
        'movement_date' => 'date',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    // Relationships
    public function stationaryItem()
    {
        return $this->belongsTo(StationaryItem::class);
    }

    public function fromGodown()
    {
        return $this->belongsTo(Godown::class, 'from_godown_id');
    }

    public function toGodown()
    {
        return $this->belongsTo(Godown::class, 'to_godown_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByMovementType($query, $movementType)
    {
        return $query->where('movement_type', $movementType);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('movement_date', [$startDate, $endDate]);
    }

    public function scopeByMovedBy($query, $movedBy)
    {
        return $query->where('moved_by', $movedBy);
    }

    // Methods
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function getDaysSinceMovementAttribute()
    {
        return $this->movement_date->diffInDays(now());
    }

    public function getMovementDescriptionAttribute()
    {
        return "{$this->quantity} units of {$this->stationaryItem->name} moved from {$this->fromGodown->name} to {$this->toGodown->name}";
    }
}
