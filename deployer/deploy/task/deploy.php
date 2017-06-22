<?php

namespace Deployer;

task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:wp:core',
    'deploy:clear_paths',
    'php:clear_cache_cli',
    'deploy:symlink',
    'php:clear_cache_http',
    'cleanup',
])->desc('Deploy your Wordpress');