<?php

namespace SourceBroker\DeployerExtendedWordpress;

use SourceBroker\DeployerLoader\Load;

class Loader
{
    public function __construct()
    {
        (new ConfigFile())->createConfigFileIfDoesNotExists(getcwd() . '/wp-config-local.php');
        /** @noinspection PhpIncludeInspection */
        require_once 'recipe/common.php';
        new Load([
                ['path' => 'vendor/sourcebroker/deployer-instance/deployer'],
                ['path' => 'vendor/sourcebroker/deployer-extended/deployer'],
                ['path' => 'vendor/sourcebroker/deployer-extended-database/deployer'],
                ['path' => 'vendor/sourcebroker/deployer-extended-media/deployer'],
                ['path' => 'vendor/sourcebroker/deployer-extended-wordpress/deployer']
            ]
        );
    }
}
