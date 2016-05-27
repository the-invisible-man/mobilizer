<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingsMetadata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings_metadata', function (Blueprint $table) {
            $table->uuid('id')->index()->unique();
            $table->uuid('fk_listing_id')->index();
            $table->boolean('dog_friendly')->nullable();
            $table->boolean('cat_friendly')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
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
