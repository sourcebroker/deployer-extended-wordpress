<?php

namespace SourceBroker\DeployerExtendedWordpress\Drivers;

use Dotenv\Dotenv;
use SourceBroker\DeployerExtended\Utility\FileUtility;

/**
 * Class WordpressDriver
 * @package SourceBroker\DeployerExtended\Drivers
 */
class WordpressDriver
{
    /**
     * @param null $absolutePathWithConfig
     * @return array
     * @throws \Exception
     * @internal param null $params
     */
    public function getDatabaseConfig($absolutePathWithConfig = null)
    {
        $dbConfig = [];
        if (file_exists($absolutePathWithConfig)) {
            /** @noinspection PhpIncludeInspection */
            require $absolutePathWithConfig;

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

        } else {
            throw new \Exception('Missing file "' . $absolutePathWithConfig . '" when trying to get Wordpress configuration file.');
        }
        return $dbConfig;

    }
}