<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Lib\Packages\Tools\Traits\UuidModel;

/**
 * Class User
 * @package App\Lib\Packages\User
 * @author Carlos Granados <carlos@polivet.org>
 */
class User extends Authenticatable
{
    use UuidModel;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $table = 'users';

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
     * @return string
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->getAttribute('first_name');
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->getAttribute('last_name');
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->getAttribute('email');
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->getAttribute('phone');
    }

    /**
     * @param bool $confirmed
     * @return $this
     */
    public function setConfirmed(bool $confirmed)
    {
        $this->setAttribute('confirmed', $confirmed);
        return $this;
    }

    /**
     * @return bool
     */
    public function getConfirmed()
    {
        return (bool)$this->getAttribute('confirmed');
    }
}
