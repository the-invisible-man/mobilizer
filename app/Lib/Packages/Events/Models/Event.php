<?php

namespace App\Lib\Packages\Events\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public $table = 'event';

    const   ID          = 'id',
            NAME        = 'name',
            DESCRIPTION = 'description',
            LOCATION_ID = 'location_id',
            USER_ID     = 'user_id';

    public function getId() : int
    {
        return (int)$this->getAttribute(self::ID);
    }

    // public function
}
