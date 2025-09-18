<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'cte_id',
        'school_id',
        'name',
        'date_of_birth',
        'age',
        'gender',
        'class',
        'father_name',
        'mother_name',
        'guardian_contact',
        'guardian_profession',
        'address',
        'district',
        'tehsil',
        'uc',
        'enrollment_type',
        'enrollment_date',
        'status',
        'dropout_reason',
        'dropout_date',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
        'dropout_date' => 'date',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function stationaryAwards()
    {
        return $this->hasMany(StationaryAward::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeByClass($query, $class)
    {
        return $query->where('class', $class);
    }

    public function scopeByDistrict($query, $district)
    {
        return $query->where('district', $district);
    }

    public function scopeDroppedOut($query)
    {
        return $query->where('status', 'dropped_out');
    }

    // Methods
    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    public function getTotalAwardsAttribute()
    {
        return $this->stationaryAwards()->sum('quantity');
    }

    public function getTotalAwardValueAttribute()
    {
        return $this->stationaryAwards()->sum('total_cost');
    }

    public function getAwardHistoryAttribute()
    {
        return $this->stationaryAwards()
            ->with(['stationaryItem', 'school'])
            ->orderBy('award_date', 'desc')
            ->get();
    }

    public function getRecentAwardsAttribute()
    {
        return $this->stationaryAwards()
            ->with('stationaryItem')
            ->where('award_date', '>=', now()->subMonths(3))
            ->orderBy('award_date', 'desc')
            ->get();
    }

    public function isDroppedOut()
    {
        return $this->status === 'dropped_out';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }
}
