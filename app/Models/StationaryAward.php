<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationaryAward extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_id',
        'stationary_item_id',
        'godown_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'award_type',
        'awarded_by',
        'award_date',
        'notes',
        'status',
        'return_date',
        'return_notes',
    ];

    protected $casts = [
        'award_date' => 'date',
        'return_date' => 'date',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
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
    public function scopeAwarded($query)
    {
        return $query->where('status', 'awarded');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    public function scopeByAwardType($query, $awardType)
    {
        return $query->where('award_type', $awardType);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('award_date', [$startDate, $endDate]);
    }

    public function scopeByAwardedBy($query, $awardedBy)
    {
        return $query->where('awarded_by', $awardedBy);
    }

    // Methods
    public function isReturned()
    {
        return $this->status === 'returned';
    }

    public function isAwarded()
    {
        return $this->status === 'awarded';
    }

    public function getDaysSinceAwardAttribute()
    {
        return $this->award_date->diffInDays(now());
    }

    public function getReturnDaysAttribute()
    {
        if ($this->return_date) {
            return $this->award_date->diffInDays($this->return_date);
        }
        return null;
    }
}
