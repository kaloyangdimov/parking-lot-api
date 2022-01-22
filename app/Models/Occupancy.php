<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupancy extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'occupancy';

    protected $casts = [
        'occupied_at' => 'datetime'
    ];

    public $fillable = [
        'vehicle_reg_number', 'discount_card_id',
        'vehicle_type', 'occupied_at'
    ];

    public function discountCard()
    {
        return $this->belongsTo('App\Models\IssuedCard', 'discount_card_id');
    }
}
