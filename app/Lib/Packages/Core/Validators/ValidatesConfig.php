<?php

namespace App\Lib\Packages\Core\Validators;

use App\Lib\Packages\Core\Exceptions\ConfigNotFoundException;

trait ValidatesConfig {

    /**
     * @var array
     */
    protected $requiredConfig = [];

    /**
     * @param array $config
     * @param array $required
     * @return array
     * @throws ConfigNotFoundException
     */
    public function validateConfig(array $config, array $required = null) : array
    {
        $required = $required === null ? $this->requiredConfig : $required;

        $notFound   = array_diff($required, array_keys($config));

        if (count($notFound)) {
            throw new ConfigNotFoundException("Missing required configuration: [" . implode(',', $notFound) . "]\nReceived [" . implode(',', $config) . "]");
        }

        return $config;
    }
}