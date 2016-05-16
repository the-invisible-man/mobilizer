<?php

namespace App\Lib\Packages\Bookings\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Lib\Packages\Tools\Traits\UuidModel;

class Booking extends Model {

    use UuidModel;

    public function user() : User
    {
        return $this->belongsTo(User::class);
    }
}