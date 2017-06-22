<?php

namespace Deployer;

set('shared_dirs', [
        'wp-content/uploads',
        'wp-content/languages',
        'wp-content/upgrade',
    ]
);

set('shared_files', [
    'wp-config-local.php',
    '.env'
]);

set('previous_release_dirs_to_copy', [
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
    '.git',
    'composer.json',
    'composer.lock',
    'composer.phar',
    '.gitignore',
    '.gitattributes',
    '.env.dist'
]);

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
set('db_default', [
    'ignore_tables_out' => [
        'cf_.*',
        'cache_.*',
    ],
    'ignore_tables_in' => [],
    'post_sql_out' => '',
    'post_sql_in' => ''
]);
set('db_databases',
    [
        'database_default' => [
            get('db_default'),
            (new \SourceBroker\DeployerExtendedWordpress\Drivers\WordpressDriver)->getDatabaseConfig(getcwd() . '/wp-config-local.php'),
        ]
    ]
);
set('db_instance',
    (new \SourceBroker\DeployerExtendedWordpress\Drivers\WordpressDriver)->getInstanceName(getcwd() . '/wp-config-local.php')
);
