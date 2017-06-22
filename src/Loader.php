<?php

namespace SourceBroker\DeployerExtendedWordpress;

class Loader
{
    public function __construct()
    {
        require_once 'recipe/common.php';

        new \SourceBroker\DeployerExtendedDatabase\Loader();
        new \SourceBroker\DeployerExtendedMedia\Loader();
        new \SourceBroker\DeployerExtended\Loader();

        \SourceBroker\DeployerExtended\Utility\FileUtility::requireFilesFromDirectoryReqursively(
            dirname((new \ReflectionClass('\SourceBroker\DeployerExtendedWordpress\Loader'))->getFileName()) . '/../deployer/'
        );
    }
}