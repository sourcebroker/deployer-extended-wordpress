<?php

namespace Deployer;

use SourceBroker\DeployerExtendedDatabase\Utility\ConsoleUtility;
use SourceBroker\DeployerInstance\Configuration;

/**
 * Replace domains in current instance.
 */
task('db:import:post_command:wp_domains', function () {

    $dumpCode = (new ConsoleUtility())->getOption('dumpcode', true);
    $dumpsForDumpCode = glob(get('db_storage_path_local') . '/' . '*dumpcode=' . $dumpCode . '*');
    if (empty($dumpsForDumpCode)) {
        throw new \Exception('Can not find dumps for dumpcode: ' . $dumpCode . '. [Error code: 1498321476975]');
    }
    $sourceInstanceName = null;
    preg_match('/\#server=(.*)\#/U', reset($dumpsForDumpCode), $match);
    if (isset($match[1])) {
        $sourceInstanceName = $match[1];
    } else {
        throw new \Exception('Can not determine source instance based on dump filename. [Error code: 1498321481427]');
    }
    $currentInstancePublicUrls = get('public_urls');
    $sourceInstancePublicUrls = Configuration::getHost($sourceInstanceName)->get('public_urls');
    if (count($currentInstancePublicUrls) === count($sourceInstancePublicUrls)) {
        $publicUrlsPairs = array_combine($sourceInstancePublicUrls, $currentInstancePublicUrls);
        foreach ($publicUrlsPairs as $publicUrlOld => $publicUrlNew) {
            runLocally('{{local/bin/wp}} search-replace ' .
                escapeshellarg(rtrim($publicUrlOld, '/')) . ' '
                . escapeshellarg(rtrim($publicUrlNew, '/'))
            );
        }
    } else {
        throw new \Exception('The amount of public_urls in source and current instance must be the same. [Error code: 1498321606442]');
    }
});
