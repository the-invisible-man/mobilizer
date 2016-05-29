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
        $notFound   = array_diff($this->requiredConfig, array_keys($config));

        if (count($notFound)) {
            throw new ConfigNotFoundException("Missing required configuration: [" . implode(',', $notFound) . "]\nReceived [" . implode(',', $config) . "]");
        }

        return $config;
    }
}