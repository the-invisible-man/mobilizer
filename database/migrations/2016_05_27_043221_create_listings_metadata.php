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
            $table->uuid('fk_listing_route_id')->nullable()->index();
            $table->boolean('dog_friendly')->nullable();
            $table->boolean('cat_friendly')->nullable();
            $table->time('time_of_day')->nullable();
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
        Schema::drop('listings_metadata');
    }
}
