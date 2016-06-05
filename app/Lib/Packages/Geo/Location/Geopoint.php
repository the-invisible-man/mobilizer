<?php

namespace App\Lib\Packages\Geo\Location;

/**
 * Class Geopoint
 * @package App\Lib\Packages\Geo\Location
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
class Geopoint {

    /**
     * @var array
     */
    private $point;

    const LAT = 0, LONG = 1;

    /**
     * Geopoint constructor.
     * @param array $point
     */
    public function __construct(array $point = [0, 0])
    {
        $this->validate($point);

        $this->setLat($point[self::LAT]);
        $this->setLong($point[self::LONG]);
    }

    private function validate(array $point)
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
     * @param $lat
     * @return $this
     */
    public function setLat($lat)
    {
        $this->point[self::LAT] = $this->format($lat);
        return $this;
    }

    /**
     * @param $long
     * @return $this
     */
    public function setLong($long)
    {
        $this->point[self::LONG] = $this->format($long);
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->point;
    }

    /**
     * @param $ll
     * @return float
     */
    private function format($ll)
    {
        return $ll;
        return number_format((float)$ll, 6, '.', '');
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