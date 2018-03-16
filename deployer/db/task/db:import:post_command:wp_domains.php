<?php

namespace Deployer;

task('db:import:post_command:wp_domains', function () {
    if (input()->getOption('dumpcode')) {
        $dumpCode = input()->getOption('dumpcode');
    } else {
        throw new \Exception('No dumpcode set. [Error code: 1498321469492]');
    }
    $dumpsForDumpCode = glob(get('db_storage_path_current') . '/' . '*dumpcode:' . $dumpCode . '*');
    if (empty($dumpsForDumpCode)) {
        throw new \Exception('Can not find dumps for dumpcode: ' . $dumpCode . '. [Error code: 1498321476975]');
    }
    $sourceInstanceName = null;
    preg_match('/\#server:(.*)\#/U', reset($dumpsForDumpCode), $match);
    if (isset($match[1])) {
        $sourceInstanceName = $match[1];
    } else {
        throw new \Exception('Can not determine source instance based on dump filename. [Error code: 1498321481427]');
    }
    $currentInstancePublicUrls = get('public_urls');
    if (isset(Deployer::get()->environments[$sourceInstanceName])) {
        $sourceInstance = Deployer::get()->environments[$sourceInstanceName];
    } else {
        throw new \Exception('Can not find instance with name: ' . $sourceInstanceName . ' [Error code: 1498321487045]');
    }
    $sourceInstancePublicUrls = $sourceInstance->get('public_urls');
    if (count(get('public_urls')) === count($sourceInstance->get('public_urls'))) {
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