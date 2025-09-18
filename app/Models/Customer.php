<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
        'phone_number',
        'date_of_birth',
        'age',
        'gender',
        'hobby_1',
        'hobby_2',
        'hobby_3',
        'favorite_food',
        'profession',
        'religion',
        'recommended_by',
        'number_of_children',
        'residential_district',
        'consent_scholarship',
        'consent_whatsapp',
        'membership_date',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'membership_date' => 'date',
        'consent_scholarship' => 'boolean',
        'consent_whatsapp' => 'boolean',
    ];

    // Relationships
    public function sales()
    {
        return $this->hasMany(Sale::class);
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

    public function scopeByDistrict($query, $district)
    {
        return $query->where('residential_district', $district);
    }

    // Methods
    public function getTotalPurchasesAttribute()
    {
        return $this->sales()->sum('total_amount');
    }

    public function getTotalDonationsAttribute()
    {
        return $this->sales()->sum('donation_amount');
    }

    public function getPurchaseHistoryAttribute()
    {
        return $this->sales()->with('stationaryItem')->orderBy('sale_date', 'desc')->get();
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    public function getHobbiesArrayAttribute()
    {
        return array_filter([$this->hobby_1, $this->hobby_2, $this->hobby_3]);
    }
}
