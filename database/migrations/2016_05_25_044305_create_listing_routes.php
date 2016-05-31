<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listing_routes', function (Blueprint $table) {
            $table->uuid('id')->index()->unique();
            $table->text('overview_path');
            $table->string('status')->default('queued');
            $table->integer('attempts')->default(0);
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
        Schema::drop('listing_routes');
    }
}
