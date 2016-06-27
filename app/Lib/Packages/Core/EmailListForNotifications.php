<?php

namespace App\Lib\Packages\Core;

use App\Lib\Packages\Tools\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailListForNotifications
 * @package App\Lib\Packages\Core
 */
class EmailListForNotifications extends Model
{
    use UuidModel;

    /**
     * @var string
     */
    protected $table    = 'email_list_for_notifications';

    /**
     * @var bool
     */
    public $increments  = false;

    /**
     * @var array
     */
    protected $fillable = ['email', 'query'];

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->getAttribute('email');
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email)
    {
        $this->setAttribute('email', $email);
        return $this;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->getAttribute('query');
    }

    /**
     * @param string $query
     * @return $this
     */
    public function setQuery(string $query)
    {
        $this->setAttribute('query', $query);
        return $this;
    }
}