<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOccupancyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('occupancy', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_reg_number')->unique();
            $table->bigInteger('discount_card_id')->unsigned()->nullable();
            $table->integer('vehicle_type');
            $table->dateTime('occupied_at');

            $table->foreign('discount_card_id')->references('id')->on('issued_cards');
            $table->foreign('vehicle_type')->references('type')->on('vehicle_rates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('occupancy');
    }
}
