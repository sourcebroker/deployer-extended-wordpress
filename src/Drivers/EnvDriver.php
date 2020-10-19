<?php

namespace SourceBroker\DeployerExtendedWordpress\Drivers;

use Exception;
use RuntimeException;
use SourceBroker\DeployerInstance\Env;

class EnvDriver
{
    /**
     * @param null $absolutePathWithConfig
     * @return array
     */
    public function getDatabaseConfig($absolutePathWithConfig = null): array
    {
        $this->loadEnv($absolutePathWithConfig);
        return [
            'host' => $this->getEnv('DB_HOST'),
            'port' => $this->getEnv('DB_PORT'),
            'dbname' => $this->getEnv('DB_NAME'),
            'user' => $this->getEnv('DB_USER'),
            'password' => $this->getEnv('DB_PASSWORD'),
        ];
    }

    /**
     * @param null $absolutePathWithConfig
     * @return string
     * @throws Exception
     */
    public function getInstanceName($absolutePathWithConfig = null): string
    {
        $this->loadEnv($absolutePathWithConfig);
        $instanceName = $this->getEnv($this->getInstanceEnvName());
        if ($instanceName === null) {
            throw new RuntimeException("\nWP_INSTANCE/WP_ENV env variable is not set. \nIf this is your local instance then please put following line: \nWP_ENV=development (or WP_INSTANCE=dev if you have instance based settings)\nin configuration file: ' . $absolutePathWithConfig . '\n\n");
        }
        return $instanceName;
    }

    /**
     * @param null $absolutePathWithConfig
     */
    private function loadEnv($absolutePathWithConfig = null): void
    {
        $env = new Env();
        $env->load($absolutePathWithConfig, $this->getInstanceEnvName());
    }

    /**
     * @param string $envName
     * @return mixed|null
     */
    private function getEnv(string $envName)
    {
        $env = new Env();
        return $env->get($envName);
    }

    /**
     * @return mixed
     */
    private function getInstanceEnvName()
    {
        return $_ENV('WP_INSTANCE') ?: $_ENV('WP_ENV');
    }
}

