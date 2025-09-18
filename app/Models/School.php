<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_id',
        'address',
        'district',
        'tehsil',
        'uc',
        'principal_name',
        'contact_number',
        'email',
        'total_students',
        'total_teachers',
        'school_type',
        'status',
        'registration_date',
        'notes',
    ];

    protected $casts = [
        'registration_date' => 'date',
    ];

    // Relationships
    public function students()
    {
        return $this->hasMany(Student::class);
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

    public function scopeByDistrict($query, $district)
    {
        return $query->where('district', $district);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('school_type', $type);
    }

    // Methods
    public function getActiveStudentsAttribute()
    {
        return $this->students()->where('status', 'active')->count();
    }

    public function getTotalAwardsAttribute()
    {
        return $this->stationaryAwards()->sum('quantity');
    }

    public function getTotalAwardValueAttribute()
    {
        return $this->stationaryAwards()->sum('total_cost');
    }

    public function getAwardsByClassAttribute()
    {
        return $this->stationaryAwards()
            ->join('students', 'stationary_awards.student_id', '=', 'students.id')
            ->selectRaw('students.class, SUM(stationary_awards.quantity) as total_quantity, SUM(stationary_awards.total_cost) as total_cost')
            ->groupBy('students.class')
            ->get();
    }

    public function getAwardsByGenderAttribute()
    {
        return $this->stationaryAwards()
            ->join('students', 'stationary_awards.student_id', '=', 'students.id')
            ->selectRaw('students.gender, SUM(stationary_awards.quantity) as total_quantity, SUM(stationary_awards.total_cost) as total_cost')
            ->groupBy('students.gender')
            ->get();
    }
}
