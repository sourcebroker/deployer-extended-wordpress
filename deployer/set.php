<?php

namespace Deployer;

set('default_timeout', 900);

set('local/bin/wp', function () {
    return './vendor/bin/wp';
});

set('shared_dirs', [
        'wp-content/uploads',
        'wp-content/languages',
        'wp-content/upgrade',
    ]
);

set('shared_files', [
    'wp-config-local.php',
    '.htaccess',
]);

set('copy_dirs', [
    'wp-content/plugins/'
]);

set('writable_dirs', [
        'wp-content/uploads',
        'wp-content/plugins',
        'wp-content/languages',
        'wp-content/upgrade',
    ]
);

set('clear_paths', [
    '.envrc',
    '.git',
    '.gitattributes',
    '.gitignore',
    '.php_cs',
    'composer.json',
    'composer.lock',
    'composer.phar',
    'license.txt',
    'phpstan.neon',
    'readme.html',
    'wp-config-local.php.dist'
]);

// Look on https://github.com/sourcebroker/deployer-extended#buffer-start for docs
set('buffer_config', [
        'index.php' => [
            'entrypoint_filename' => 'index.php',
        ],
        'wp-admin/index.php' => [
            'entrypoint_filename' => 'wp-admin/index.php',
        ]
    ]
);

// Look https://github.com/sourcebroker/deployer-extended-media for docs
set('media',
    [
        'filter' => [
            '+ /wp-content/',
            '- /wp-content/mu-plugins/*',
            '- /wp-content/themes/*',
            '+ /wp-content/**',
            '+ /wp-admin/',
            '+ /wp-admin/**',
            '+ /wp-includes/',
            '+ /wp-includes/**',
            '+ .htaccess',
            '+ wp-activate.php',
            '+ wp-blog-header.php',
            '+ wp-comments-post.php',
            '+ wp-config-sample.php',
            '+ wp-config.php',
            '+ wp-cron.php',
            '+ wp-links-opml.php',
            '+ wp-load.php',
            '+ wp-login.php',
            '+ wp-mail.php',
            '+ wp-settings.php',
            '+ wp-signup.php',
            '+ wp-trackback.php',
            '+ xmlrpc.php',
            '+ index.php',
            '- *'
        ]
    ]);


set('default_stage', function () {
    return (new \SourceBroker\DeployerExtendedWordpress\Driver)
        ->getInstanceName(getcwd() . '/wp-config-local.php');
});

// Return current instance name. Based on that scripts knows from which server() takes the data to database operations.
set('current_stage', function () {
    return (new \SourceBroker\DeployerExtendedWordpress\Driver)
        ->getInstanceName(getcwd() . '/wp-config-local.php');
});

set('target_stage', function () {
    return !empty(input()->getArgument('stage')) ? input()->getArgument('stage') : get('default_stage');
});

set('db_default', [
    'ignore_tables_out' => [],
    'post_sql_in' => '',
    'post_command' => ['{{local/bin/deployer}} db:import:post_command:wp_domains']
]);

set('db_databases',
    [
        'database_default' => [
            get('db_default'),
            function () {
                return (new \SourceBroker\DeployerExtendedWordpress\Driver)
                    ->getDatabaseConfig(getcwd() . '/wp-config-local.php');
            }
        ]
    ]
);

// Look https://github.com/sourcebroker/deployer-extended-database#db-dumpclean for docs
set('db_dumpclean_keep', [
    '*' => 5,
    'live' => 10,
]);
