<?php

namespace App\Lib\Packages\Bookings;

use App\Lib\Packages\Bookings\Models\Booking;
use Illuminate\Database\DatabaseManager;

class BookingBuilder {

    private $db;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->db = $databaseManager->connection();
    }

    /**
     * @param array $data
     * @return Booking
     */
    public function build(array $data) : Booking
    {

    }
}