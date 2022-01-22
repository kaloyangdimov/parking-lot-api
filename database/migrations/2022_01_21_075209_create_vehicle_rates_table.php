<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\VehicleRate;

class CreateVehicleRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_rates', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->index()->unique();
            $table->double('day_rate');
            $table->double('night_rate');
        });

        VehicleRate::create([
            'type' => VehicleRate::VEHICLE_TYPE_CAR,
            'day_rate' => 3.00,
            'night_rate' => 2.00,
        ]);
        VehicleRate::create([
            'type' => VehicleRate::VEHICLE_TYPE_VAN,
            'day_rate' => 6.00,
            'night_rate' => 4.00,
        ]);
        VehicleRate::create([
            'type' => VehicleRate::VEHICLE_TYPE_TRUCK,
            'day_rate' => 12.00,
            'night_rate' => 8.00,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_rates');
    }
}
