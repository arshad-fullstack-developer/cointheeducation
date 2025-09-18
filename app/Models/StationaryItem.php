<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationaryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'article',
        'quality',
        'color',
        'company',
        'size',
        'volume',
        'shape',
        'cte_reck_number',
        'unit_cost',
        'selling_price',
        'profit_margin',
        'min_stock_level',
        'expiry_date',
        'status',
        'description',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'unit_cost' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'profit_margin' => 'decimal:2',
    ];

    protected $appends = ['current_stock'];

    // Relationships
    public function procurements()
    {
        return $this->hasMany(Procurement::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function stationaryAwards()
    {
        return $this->hasMany(StationaryAward::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
                    ->where('expiry_date', '>=', now());
    }

    // Methods
    public function getCurrentStockAttribute()
    {
        $procured = $this->procurements()->where('status', 'received')->sum('quantity');
        $sold = $this->sales()->sum('quantity');
        $awarded = $this->stationaryAwards()->sum('quantity');
        $movedOut = $this->stockMovements()->where('movement_type', 'out')->sum('quantity');
        $movedIn = $this->stockMovements()->where('movement_type', 'in')->sum('quantity');
        
        $stock = $procured - $sold - $awarded - $movedOut + $movedIn;
        return max(0, $stock);
    }

    public function isLowStock()
    {
        return $this->current_stock <= $this->min_stock_level;
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
