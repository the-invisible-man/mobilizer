<?php

namespace App\Lib\Packages\Tools\Traits;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UuidModel
 * @package Lib\Packages\Tools\Traits
 * @author Carlos Granados <carlos@polivet.org>
 */
trait UuidModel {

    protected static $idColumn = 'id';

    public static function bootUuidModel()
    {
        static::creating(function (Model $model) {
            $model->{static::$idColumn} = Uuid::uuid4()->toString();
        });

        static::saving(function (Model $model) {
            $currentUuid = $model->getOriginal(static::$idColumn);

            if ($currentUuid !== $model->{static::$idColumn}) {
                $model->{static::$idColumn} = $currentUuid;
            }
        });
    }
}