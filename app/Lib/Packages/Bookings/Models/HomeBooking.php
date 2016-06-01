<?php

namespace App\Lib\Packages\Bookings\Models;

use App\Lib\Packages\Bookings\Contracts\AbstractBooking;

/**
 * Class HomeBooking
 * @package App\Lib\Packages\Bookings\Models
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class HomeBooking extends AbstractBooking {

    const ListingType = 'H';
}