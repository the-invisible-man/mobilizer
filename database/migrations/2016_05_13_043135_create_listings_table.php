<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->uuid('id')->index()->unique();
            $table->uuid('fk_user_id')->index();
            $table->uuid('fk_address_id')->index();
            $table->string('party_name');
            $table->char('type', 1);
            $table->date('starting_date');
            $table->date('ending_date');
            $table->integer('max_occupants');
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
