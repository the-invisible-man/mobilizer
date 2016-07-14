<?php

namespace App\Lib\Packages\Core;

use App\Lib\Packages\Tools\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailListForNotifications
 *
 * @package     App\Lib\Packages\Core
 * @copyright   Copyright (c) Polivet.org
 * @author      Carlos Granados <granados.carlos91@gmail.com>
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
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

    /**
     * @return int
     */
    public function getTotalPeople() : int
    {
        return (int)$this->getAttribute('total_people');
    }

    /**
     * @param int $totalPeople
     * @return $this
     */
    public function setTotalPeople(int $totalPeople)
    {
        $this->setAttribute('total_people', $totalPeople);
        return $this;
    }

    /**
     *
     * @return bool
     */
    public function wasNotified()
    {
        return (bool)$this->getAttribute('notified');
    }

    /**
     * Gets actual value, casted into int
     * @return bool
     */
    public function getNotified()
    {
        return (int)$this->getAttribute('notified');
    }

    /**
     * @param int $notified
     * @return $this
     */
    public function setNotified(int $notified)
    {
        // We only want to save 1 or 0
        $this->setAttribute('notified', (int)(bool)$notified);
        return $this;
    }
}