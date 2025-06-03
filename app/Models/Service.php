<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function spaces()
    {
        return $this->belongsToMany(Space::class, 'space_service_prices')
                    ->withPivot('price', 'commission_percentage')
                    ->withTimestamps();
    }

    public function staff()
    {
        return $this->belongsToMany(Staff::class, 'service_staff', 'service_id', 'staff_id')
                    ->withTimestamps();
    }
}
