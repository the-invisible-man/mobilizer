<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->index()->unique();
            $table->uuid('fk_users_id')->index();
            $table->uuid('fk_address_id')->index();
            $table->integer('total_people');
            $table->string('message');
            $table->boolean('brings_dog');
            $table->boolean('brings_cat');
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
