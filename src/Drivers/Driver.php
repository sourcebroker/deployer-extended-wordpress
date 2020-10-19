<?php

namespace SourceBroker\DeployerExtendedWordpress\Drivers;

use Exception;

class Driver
{
    public function getDatabaseConfig($absolutePathWithConfig = null): array
    {
        $dbConfig = [];
        if (defined('DB_NAME')) {
            $dbConfig['dbname'] = DB_NAME;
        }
        if (defined('DB_USER')) {
            $dbConfig['user'] = DB_USER;
        }
        if (defined('DB_PASSWORD')) {
            $dbConfig['password'] = DB_PASSWORD;
        }
        if (defined('DB_HOST')) {
            $hostParts = explode(':', DB_HOST);
            $dbConfig['host'] = count($hostParts) > 1 ? $hostParts[0] : DB_HOST;
            $dbConfig['port'] = count($hostParts) > 1 ? $hostParts[1] : 3306;
        }
        if (defined('DB_PORT')) {
            $dbConfig['port'] = DB_PORT;
        }
        return $dbConfig;
    }

    /**
     * @throws Exception
     */
    public function getInstanceName($absolutePathWithConfig = null): string
    {
        $instanceName = strtolower(getenv('INSTANCE'));
        if (empty($instanceName)) {
            throw new \RuntimeException("\nINSTANCE env variable is not set. \nIf this is your local instance then please put following line: \nputenv('INSTANCE=local');  \nin configuration file: ' . $absolutePathWithConfig . '\n\n");
        }
        return $instanceName;
    }

    /**
     * @throws Exception
     */
    private function readConfigFile($absolutePathWithConfig = null): void
    {
        if (file_exists($absolutePathWithConfig)) {
            /** @noinspection PhpIncludeInspection */
            require_once $absolutePathWithConfig;
        } else {
            throw new \RuntimeException('Missing file "' . $absolutePathWithConfig . '" when trying to get Wordpress configuration file.');
        }
    }
}
