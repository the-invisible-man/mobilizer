<?php

namespace App\Lib\Packages\Core\Validators;

use App\Lib\Packages\Core\Exceptions\ConfigNotFoundException;

trait ConfigValidatorTrait {

    /**
     * @var array
     */
    protected $requiredConfig = [];

    /**
     * @param array $config
     * @return array
     * @throws ConfigNotFoundException
     */
    public function validateConfig(array $config) : array
    {
        $notFound   = array_diff($this->requiredConfig, $config);

        if (count($notFound)) {
            throw new ConfigNotFoundException("Missing required configuration: [" . implode(',', $notFound) . "]");
        }

        return $config;
    }
}