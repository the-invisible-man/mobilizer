<?php

namespace App\Lib\Packages\Listings\Contracts;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractListing implements \JsonSerializable {

    /**
     * @var Model
     */
    private $data;

    abstract public function getData() : array;

    /**
     * @param Model $model
     * @return AbstractListing
     */
    public function setModel(Model $model) : static
    {
        $this->data = $model;
        return $this;
    }

    public function jsonSerialize() {
        return json_encode($this->getData());
    }
}