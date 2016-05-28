<?php

namespace App\Lib\Packages\Core\Contracts;

interface ServiceFactory {

    public function service(string $name);

}