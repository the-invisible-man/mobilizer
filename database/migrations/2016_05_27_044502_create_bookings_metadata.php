<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsMetadata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings_metadata', function (Blueprint $table) {
            $table->uuid('id')->unique()->index();
            $table->uuid('fk_booking_id')->index();
            $table->boolean('brings_dog')->nullable();
            $table->boolean('brings_cat')->nullable();
            $table->string('additional_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
