<?php

namespace App\Lib\Packages\Geo\Address;

/**
 * Class Geopoint
 * @package App\Lib\Packages\Geo\Address
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class Geopoint {

    /**
     * @var array
     */
    private $point;

    const LAT = 0, LONG =1;

    /**
     * Geopoint constructor.
     * @param array $point
     */
    public function __construct(array $point)
    {
        $this->validate($point);

        $this->point[self::LAT]     = (float)$point[self::LAT];
        $this->point[self::LONG]    = (float)$point[self::LONG];
    }

    private function validate(array $point) : array
    {
        // We need to know that we have at least two items in the
        // array and that they're scalars. I'm not sure whether to
        // react to having more than 2 items so we'll just ignore.
        if (count($point) < 2) {
            throw new \InvalidArgumentException("Cannot initialize Geopoint: Point array does not have 2 items (lat/long)");
        }

        if (!is_scalar($point[self::LAT])) {
            throw new \InvalidArgumentException("Cannot initialize Geopoint: Item at subset 0 is not a scalar");
        } elseif (!is_scalar($point[self::LONG])) {
            throw new \InvalidArgumentException("Cannot initialize Geopoint: Item at subset 1 is not a scalar");
        }
    }

    /**
     * @return float
     */
    public function getLat() : float
    {
        return $this->point[self::LAT];
    }

    /**
     * @return float
     */
    public function getLong() : float
    {
        return $this->point[self::LONG];
    }


}