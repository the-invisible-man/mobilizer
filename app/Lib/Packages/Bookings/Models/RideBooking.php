<?php

namespace App\Lib\Packages\Bookings\Models;

use App\Lib\Packages\Bookings\Contracts\AbstractBooking;

/**
 * Class RideBooking
 * @package App\Lib\Packages\Bookings\Models
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class RideBooking extends AbstractBooking {

    const ListingType = 'R';
}