<?php

namespace SourceBroker\DeployerExtendedWordpress;

/**
 * Class ConfigFile
 * @package SourceBroker\DeployerExtended\Drivers
 */
class ConfigFile
{
    /**
     * @param $absolutePathWithConfig
     * @throws \Exception
     */
    public function createConfigFileIfDoesNotExists($absolutePathWithConfig)
    {
        if (!file_exists($absolutePathWithConfig)) {
            $host = getenv('MYSQL_HOST');
            $port = getenv('MYSQL_HOST_PORT') ? getenv('MYSQL_HOST_PORT') : 3306;
            $username = getenv('MYSQL_USER');
            $password = getenv('MYSQL_PASSWORD');
            $databaseName = empty(getenv('MYSQL_DATABASE')) ? '' : getenv('MYSQL_DATABASE');
            if (empty($databaseName)) {
                exec('git config --get remote.origin.url', $output);
                preg_match('/\/([a-zA-Z0-9-_\.]+)\.git/', $output[0], $match);
                if ((isset($output[0]) && strlen($output[0]) === 0) || empty($match[1])) {
                    throw new \Exception('Can not get git repo name from "git config --get remote.origin.url" command. Its needed to create database.');
                }
                $databaseBaseName = 'wp_' . $match[1];
                $databaseBaseName = str_replace(['.', '-'], ['_', '_'], $databaseBaseName);
                if (!empty(getenv('MYSQL_USER')) && !empty(getenv('MYSQL_PASSWORD'))) {
                    $databaseName = $this->createDatabaseIfDoesNotExists($host, $port, getenv('MYSQL_USER'),
                        getenv('MYSQL_PASSWORD'), $databaseBaseName);
                }
                if (empty($databaseName) && !empty(getenv('MYSQL_ROOT_USER')) && !empty(getenv('MYSQL_ROOT_PASSWORD'))) {
                    $databaseName = $this->createDatabaseIfDoesNotExists($host, $port, getenv('MYSQL_ROOT_USER'),
                        getenv('MYSQL_ROOT_PASSWORD'), $databaseBaseName);
                    $username = getenv('MYSQL_ROOT_USER');
                    $password = getenv('MYSQL_ROOT_PASSWORD');
                }
            }
            $envDist = file_get_contents(dirname($absolutePathWithConfig) . '/wp-config-local.php.dist');
            $replace = [
                'INSTANCE' => getenv('INSTANCE'),
                'MYSQL_DATABASE' => $databaseName,
                'MYSQL_USER' => $username,
                'MYSQL_PASSWORD' => $password,
                'MYSQL_HOST' => $host,
                'MYSQL_HOST_PORT' => $port,
            ];
            $envDistMarkersReplaced = preg_replace_callback(
                "/\\{([A-Za-z0-9_:]+)\\}/",
                function ($match) use ($replace) {
                    return $replace[$match[1]];
                },
                $envDist
            );
            file_put_contents($absolutePathWithConfig, $envDistMarkersReplaced);
        }
    }

    private function createDatabaseIfDoesNotExists($host, $port, $user, $password, $databaseBaseName)
    {
        $databaseName = null;
        $mysqli = new \mysqli($host, $user, $password, '', $port);
        if (!$mysqli->connect_errno) {
            $i = 0;
            while ($i < 20) {
                $databaseName = $databaseBaseName . ($i ? '_' . $i : '');
                $result = $mysqli->query("SELECT COUNT(DISTINCT `table_name`) AS table_counter FROM `information_schema`.`columns` WHERE `table_schema` = '" . $databaseName . "'");
                if (!empty($result) && intval($result->fetch_assoc()['table_counter']) == 0) {
                    $mysqli->query("CREATE DATABASE IF NOT EXISTS `" . $databaseName . "`");
                    break;
                } else {
                    $i++;
                }
            }
        }
        return $databaseName;
    }
}
