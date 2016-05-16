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
            $table->uuid('fk_users_id')->index();
            $table->uuid('fk_address_id')->index();
            $table->string('party_name');
            $table->char('type', 1);
            $table->string('message');
            $table->date('leaving')->nullable();
            $table->date('starting');
            $table->date('ending');
            $table->integer('max_occupants');
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->boolean('dog_friendly');
            $table->boolean('cat_friendly');
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
