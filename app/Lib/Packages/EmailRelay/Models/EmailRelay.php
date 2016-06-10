<?php

namespace App\Lib\Packages\EmailRelay\Models;

use App\Lib\Packages\Tools\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailRelay
 * @package App\Lib\Packages\EmailRelay\Models
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class EmailRelay extends Model
{
    use UuidModel;

    const   ID              = 'id',
            FK_USER_ID      = 'fk_user_id',
            FK_BOOKING_ID   = 'fk_booking_id',
            MUTED           ='muted';

    /**
     * @var string
     */
    protected $table = 'email_relay';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id)
    {
        $this->setAttribute(self::ID, $id);
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setFkUserId(string $id)
    {
        $this->setAttribute(self::FK_USER_ID, $id);
        return $this;
    }

    /**
     * @return string
     */
    public function getFkUserId()
    {
        return $this->getAttribute(self::FK_USER_ID);
    }
}