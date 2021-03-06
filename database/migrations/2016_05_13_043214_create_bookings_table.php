<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Lib\Packages\Bookings\Contracts\BaseBooking;

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
            $table->uuid('fk_user_id')->index();
            $table->uuid('fk_listing_id')->index();
            $table->integer('total_people');
            $table->string('status')->default(BaseBooking::STATUS_PENDING);
            $table->text("additional_info");
            $table->char('type', 1);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['fk_user_id', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bookings');
    }
}
