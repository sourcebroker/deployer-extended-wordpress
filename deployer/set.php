<?php

namespace Deployer;

set('branch_detect_to_deploy', false);

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
    'config/.env.local',
    '.htaccess',
]);

set('copy_dirs_ignore_existing', [
    'wp-content/plugins/',
    'wp-content/themes/'
]);

set('copy_files_ignore_existing', [
    'wp-content/mu-plugins/'
]);

set('writable_dirs', [
        'wp-content/uploads',
        'wp-content/plugins',
        'wp-content/languages',
        'wp-content/upgrade',
    ]
);

set('clear_paths', [
    '.ddev',
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

set('default_stage', function () {
    return (new \SourceBroker\DeployerExtendedWordpress\Drivers\EnvDriver)
        ->getInstanceName(getcwd() . '/config');
});

// Look https://github.com/sourcebroker/deployer-extended-media for docs
set('media_allow_copy_live', false);
set('media_allow_link_live', false);
set('media_allow_pull_live', false);
set('media_allow_push_live', false);
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

// Look https://github.com/sourcebroker/deployer-extended-database for docs
set('db_allow_copy_live', false);
set('db_allow_pull_live', false);
set('db_allow_push_live', false);
set('db_databases',
    [
        'database_default' => [
            [
                'ignore_tables_out' => [],
                'post_sql_in' => '',
                'post_command' => ['export $(cat config/.env | grep PATH | xargs) && {{local/bin/deployer}} db:import:post_command:wp_domains']
            ],
            function () {
                return (new \SourceBroker\DeployerExtendedWordpress\Drivers\EnvDriver())
                    ->getDatabaseConfig(getcwd() . '/config');
            }
        ]
    ]
);

// Look https://github.com/sourcebroker/deployer-extended-database#db-dumpclean for docs
set('db_dumpclean_keep', [
    '*' => 5,
    'live' => 10,
]);
