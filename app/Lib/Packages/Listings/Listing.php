<?php

namespace App\Lib\Packages\Listings;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Lib\Packages\Geo\Address;
use App\Lib\Packages\Tools\Traits\UuidModel;

/**
 * Class Listing
 * @package App\Lib\Packages\Listings
 * @author Carlos Granados <carlos@polivet.org>
 */
class Listing extends Model {

    use UuidModel;

    /**
     * @return User
     */
    public function user() : User
    {
        $this->belongsTo(User::class);
    }

    /**
     * @return Address
     */
    public function address() : Address
    {
        $this->hasOne(Address::class);
    }
}