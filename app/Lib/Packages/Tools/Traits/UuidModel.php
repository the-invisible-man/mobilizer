<?php

namespace App\Lib\Packages\Tools\Traits;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UuidModel
 *
 * @package     Lib\Packages\Tools\Traits
 * @copyright   Copyright (c) Polivet.org
 * @author      Carlos Granados <granados.carlos91@gmail.com>
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 */
trait UuidModel {

    protected static $idColumn = 'id';

    public static function bootUuidModel()
    {
        static::creating(function (Model $model) {
            $model->setAttribute(static::$idColumn, Uuid::uuid4()->toString());
            return true;
        });

        static::saving(function (Model $model) {
            $currentUuid = $model->getOriginal(static::$idColumn);

            if ($currentUuid !== $model->{static::$idColumn}) {
                $model->setAttribute(static::$idColumn, $currentUuid);
            }
        });
    }
}