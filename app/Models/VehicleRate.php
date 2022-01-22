<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleRate extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'type',
        'day_rate',
        'night_rate'
    ];

    const VEHICLE_TYPE_CAR = 1;
    const VEHICLE_TYPE_VAN = 2;
    const VEHICLE_TYPE_TRUCK = 3;

    const SIZE_A = 1;
    const SIZE_B = 2;
    const SIZE_C = 4;

    public static function getVehicleSizes()
    {
        return [
            self::VEHICLE_TYPE_CAR   => self::SIZE_A,
            self::VEHICLE_TYPE_VAN   => self::SIZE_B,
            self::VEHICLE_TYPE_TRUCK => self::SIZE_C,
        ];
    }
}
