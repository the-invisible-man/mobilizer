<?php

namespace App\Lib\Packages\Listings\ListingTypes;

use App\Lib\Packages\Listings\Contracts\BaseListing;

/**
 * Class HomeListing
 *
 * @package     App\Lib\Packages\Listings\ListingTypes
 * @copyright   Copyright (c) Polivet.org
 * @author      Carlos Granados <granados.carlos91@gmail.com>
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 */
class HomeListing extends BaseListing
{
    const ListingType = 'H';

    /**
     * @var array
     */
    public static $required = [];


}