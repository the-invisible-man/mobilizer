<?php

namespace App;

use App\Lib\Packages\Bookings\Models\Booking;
use App\Lib\Packages\Listings\Listing;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lib\Packages\Tools\Traits\UuidModel;

/**
 * Class User
 * @package App\Lib\Packages\User
 * @author Carlos Granados <carlos@polivet.org>
 */
class User extends Authenticatable
{

    use UuidModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return Booking
     */
    public function bookings() : Booking
    {
        $this->hasMany(Booking::class);
    }

    /**
     * @return Listing
     */
    public function listings() : Listing
    {
        $this->hasMany(Listing::class);
    }
}
