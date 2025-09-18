<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Godown extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'description',
        'manager_name',
        'contact_number',
        'email',
        'status',
    ];

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

    public function stockMovementsFrom()
    {
        return $this->hasMany(StockMovement::class, 'from_godown_id');
    }

    public function stockMovementsTo()
    {
        return $this->hasMany(StockMovement::class, 'to_godown_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Methods
    public function getTotalStockValueAttribute()
    {
        $totalValue = 0;
        
        foreach ($this->procurements as $procurement) {
            $item = $procurement->stationaryItem;
            $currentStock = $item->current_stock;
            $totalValue += $currentStock * $procurement->unit_cost;
        }
        
        return $totalValue;
    }

    public function getLowStockItemsAttribute()
    {
        $lowStockItems = collect();
        
        foreach ($this->procurements as $procurement) {
            $item = $procurement->stationaryItem;
            if ($item->isLowStock()) {
                $lowStockItems->push($item);
            }
        }
        
        return $lowStockItems;
    }
}
