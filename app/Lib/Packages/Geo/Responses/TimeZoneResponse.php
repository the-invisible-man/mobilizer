<?php

namespace App\Lib\Packages\Geo\Responses;

class TimeZoneResponse {

    /**
     * @var string
     */
    private $timeZoneId;

    /**
     * @var int (signed)
     */
    private $dstOffset;

    /**
     * @var int
     */
    private $rawOffset;

    /**
     * @var string
     */
    private $timeZoneName;

    /**
     * @return string
     */
    public function getTimeZoneName()
    {
        return $this->timeZoneName;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setTimeZoneName(string $name)
    {
        $this->timeZoneName = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getRawOffset()
    {
        return $this->rawOffset;
    }

    /**
     * @param int $rawOffset
     * @return $this
     */
    public function setRawOffset(int $rawOffset)
    {
        $this->rawOffset = $rawOffset;
        return $this;
    }

    /**
     * @return int
     */
    public function getDstOffset()
    {
        return $this->dstOffset;
    }

    /**
     * @param int $dstOffset
     * @return $this
     */
    public function setDstOffset(int $dstOffset)
    {
        $this->dstOffset = $dstOffset;
        return $this;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setTimeZoneId(string $id)
    {
        $this->timeZoneId = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeZoneId()
    {
        return $this->timeZoneId;
    }
}