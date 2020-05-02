<?php

namespace Deployer;

task('deploy:protect_vendor', function () {
    $activePath = get('deploy_path') . '/' . (test('[ -e {{deploy_path}}/current ]') ? 'current' : 'release');
    run('echo "deny from all"  > ' . $activePath . '/vendor/.htaccess');
});
