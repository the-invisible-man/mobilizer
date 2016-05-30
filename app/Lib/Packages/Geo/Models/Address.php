<?php

namespace App\Lib\Packages\Geo\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Location
 * @package App\Lib\Packages\Geo
 * @author Carlos Granados <carlos@polivet.org>
 */
class Address extends Model {


    public function user() : User
    {
        return $this->belongsTo(User::class);
    }
}