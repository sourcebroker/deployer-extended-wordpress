<?php

namespace Deployer;

set('local/bin/wp', function () {
    $wpCliBin = null;
    if (testLocally('[ -e \'{{deploy_path}}/vendor/bin/wp\' ]')) {
        $wpCliBin = parse('{{deploy_path}}/vendor/bin/wp');
    } else {
        $wpCliBin = runLocally('which wp')->toString();
    }
    if(!$wpCliBin) {
        throw new \Exception('Can not determine wp_cli path. Make it available inside you PATH or use composer version.');
    }
    return $wpCliBin;
});

set('shared_dirs', [
        'wp-content/uploads',
        'wp-content/languages',
        'wp-content/upgrade',
    ]
);

set('shared_files', [
    'wp-config-local.php',
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
    '.git',
    '.gitattributes',
    '.gitignore',
    'composer.json',
    'composer.lock',
    'composer.phar',
    'license.txt',
    'readme.html',
    'wp-config-local.php.dist'
]);

// Look on https://github.com/sourcebroker/deployer-extended#buffer-start for docs
set('buffer_config', [
        'index.php' => [
            'entrypoint_filename' => 'index.php',
        ],
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
set('db_instance', function () {
    return (new \SourceBroker\DeployerExtendedWordpress\Drivers\WordpressDriver)
        ->getInstanceName(getcwd() . '/wp-config-local.php');
});

set('default_stage', function () {
    return (new \SourceBroker\DeployerExtendedWordpress\Drivers\WordpressDriver)
        ->getInstanceName(getcwd() . '/wp-config-local.php');
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
                return (new \SourceBroker\DeployerExtendedWordpress\Drivers\WordpressDriver)
                    ->getDatabaseConfig(getcwd() . '/wp-config-local.php');
            }
        ]
    ]
);
