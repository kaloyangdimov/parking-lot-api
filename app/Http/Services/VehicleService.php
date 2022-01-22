<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use App\Models\VehicleRate;

class VehicleService extends Controller
{
    public function getRatesForType(int $type)
    {
        return VehicleRate::firstWhere('type', $type);
    }
}
