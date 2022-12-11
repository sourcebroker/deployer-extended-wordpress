<?php

namespace Deployer;

task('deploy', [

    // Standard deployer task.
    'deploy:info',

    // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-lock
    'deploy:check_lock',

    // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-composer-install
    'deploy:check_composer_install',

    // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-branch-local
    'deploy:check_branch_local',

    // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-branch
    'deploy:check_branch',

    // Standard deployer task.
    'deploy:check_remote',

    // Standard deployer task.
    'deploy:setup',

    // Standard deployer task.
    'deploy:lock',

    // Standard deployer task.
    'deploy:release',

    // Standard deployer task.
    'deploy:update_code',

    // Standard deployer task.
    'deploy:shared',

    // Standard deployer task.
    'deploy:writable',

    // Standard deployer task.
    'deploy:vendors',

    // Local task to protect vendor folder because its publicly available.
    'deploy:protect_vendor',

    // Detect WP version and get fresh code from WordPress git repo
    'deploy:wp:core',

    // Standard deployer deploy:copy_dirs. Copy plugins from previous release of WordPress
    'deploy:copy_dirs',

    // Read more on https://github.com/sourcebroker/deployer-extended
    'file:copy_dirs_ignore_existing',

    // Read more on https://github.com/sourcebroker/deployer-extended
    'file:copy_files_ignore_existing',

    // Standard deployer task.
    'deploy:clear_paths',

    // Create database backup, compress and copy to database store.
    // Read more on https://github.com/sourcebroker/deployer-extended-database#db-backup
    'db:backup',

    // Start buffering http requests. No frontend access possible from now.
    // Read more on https://github.com/sourcebroker/deployer-extended#buffer-start
    'buffer:start',

    // Truncate caching tables
    // Read more on https://github.com/sourcebroker/deployer-extended-database#db-truncate
    'db:truncate',

    // Standard deployer task.
    'deploy:symlink',

    // Clear php cli cache.
    // Read more on https://github.com/sourcebroker/deployer-extended#cache-clear-php-cli
    'cache:clear_php_cli',

    // Clear frontend http cache.
    // Read more on https://github.com/sourcebroker/deployer-extended#cache-clear-php-http
    'cache:clear_php_http',

    // Frontend access possible again from now
    // Read more on https://github.com/sourcebroker/deployer-extended#buffer-stop
    'buffer:stop',

    // Standard deployer task.
    'deploy:unlock',

    // Standard deployer task.
    'deploy:cleanup',

    // Standard deployer task.
    'deploy:success',

])->desc('Deploy your WordPress');

after('deploy:failed', 'deploy:unlock');
