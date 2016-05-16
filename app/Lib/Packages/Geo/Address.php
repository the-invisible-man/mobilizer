<?php

namespace App\Lib\Packages\Geo;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 * @package App\Lib\Packages\Geo
 * @author Carlos Granados <carlos@polivet.org>
 */
class Address extends Model {

    public function user() : User
    {
        return $this->belongsTo(User::class);
    }
}