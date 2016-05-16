<?php

namespace App\Lib\Packages\Listings\Contracts;

interface ListingInterface {

    /**
     * @return array
     */
    public function getData() : array;
}