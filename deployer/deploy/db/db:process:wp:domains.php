<?php

namespace Deployer;

task('db:process:wp:domains', function () {
    $currentInstancePublicUrls = get('public_urls');
    $sourceServer = Deployer::get()->environments[get('db_import_source_server')];
    $sourceInstancePublicUrls = $sourceServer->get('public_urls');
    if (count(get('public_urls')) === count($sourceServer->get('public_urls'))) {
        $publicUrlsPairs = array_combine($sourceInstancePublicUrls, $currentInstancePublicUrls);
        foreach ($publicUrlsPairs as $publicUrlOld => $publicUrlNew) {
            runLocally('{{local/bin/wp}} search-replace ' . escapeshellarg($publicUrlOld) . ' ' . escapeshellarg($publicUrlNew));
        };
    } else {
        throw new \RuntimeException('The amount of public_urls in source and current instance must be the same.');
    }
});