deployer-extended-wordpress
===========================

.. contents:: :local:

What does it do?
----------------

* NOTE!! This package is working only with Deployer 4 *

This package provides deploy task for deploying WordPress with deployer (deployer.org) and additionally a tasks
to synchronize database and media files.

The deployment is simplified in order to have ability to auto-upgrade WordPress and upgrade plugins
manually by admin panel (or automatically with tools like InfiniteWP). This is a half way between
no deployment at all and deployment fully driven by composer. If you want to manage WordPress and plugins
fully with composer then check https://roots.io/


Should I use "deployer-extended-wordpress" or "deployer-extended-wordpress-composer"?
-------------------------------------------------------------------------------------

In `sourcebroker/deployer-extended-wordpress`_ the WordPress and third party plugins are installed manually. What you have in git is
basically only your theme. The good thing is that in such case you can update WordPress and plugins automatically.
This can be considered as preferable for low budget WordPress websites.

In `sourcebroker/deployer-extended-wordpress-composer`_ the WordPress and third party plugins are installed using composer.
This way you gain more control over what is installed but at the same time to install new WordPress or new plugin
version you need first to modify composer.json or do composer update (depending how big upgrade you do). Then you need
to commit composer.json / composer.lock and do deploy which will install new version of WordPress and plugins.
This is additional work that can not be easily automated. One of additional advantages of this solution is that you can
easily cleanup infected WordPress/plugins files as with each deployment all php files are fresh (part from your git
and part from composer repositories).


Dependencies
------------

This package depends on following packages:

- | `sourcebroker/deployer-extended`_
  | Package which provides some deployer tasks that can be used for any framework or CMS.

- | `sourcebroker/deployer-extended-database`_
  | Package which provides some php framework independent deployer tasks to synchronize database.

- | `sourcebroker/deployer-extended-media`_
  | Package which provides some php framework independent deployer tasks to synchronize media.

- | `wp-cli/search-replace-command`_
  | Package to change domains after database synchronization. Part of wp-cli/wp-cli utility.


Installation
------------

1) Install package with composer:
   ::

      composer require sourcebroker/deployer-extended-wordpress

2) If you are using deployer as composer package then just put following line in your deploy.php:
   ::

      new \SourceBroker\DeployerExtendedWordpress\Loader();

3) If you are using deployer as phar then put following lines in your deploy.php:
   ::

      require __DIR__ . '/vendor/autoload.php';
      new \SourceBroker\DeployerExtendedWordpress\Loader();

4) Remove task "deploy" from your deploy.php. Otherwise you will overwrite deploy task defined in
   deployer/deploy/task/deploy.php

5) Example deploy.php file:
   ::

    <?php

    namespace Deployer;

    require __DIR__.'/vendor/autoload.php';

    new \SourceBroker\DeployerExtendedWordpress\Loader();

    set('repository', 'git@my-git:my-project.git');

    server('live', '111.111.111.111')
        ->user('www-data')
        ->set('public_urls', ['https://www.example.com/'])
        ->set('deploy_path', '/var/www/example.com.live');

    server('beta', '111.111.111.111')
        ->user('www-data')
        ->set('public_urls', ['https://beta.example.com/'])
        ->set('deploy_path', '/var/www/example.com.beta');

    server('local', 'localhost')
        ->set('public_urls', ['https://example-com.dev/'])
        ->set('deploy_path', getcwd());


Mind the declaration of server('local', 'localhost'); Its needed for database tasks to decalre domain replacements,
and path to store database dumps.

Project's folders structure
---------------------------

This deployment has following assumptions:

1) WordPress source code is not in GIT in order to have ability to easily upgrade them from admin panel.
2) Plugins source code is not in GIT in order to have ability to easily upgrade them from admin panel.
3) Taking the two above points into consideration the only files in GIT will be:
   ::

        /wp-content/themes
        deploy.php
        composer.lock
        composer.json
        .htaccess
        .gitignore
        wp-config.php
        wp-config-local.php.dist


wp-config-local.php
+++++++++++++++++++
The wp-config-local.php should be excluded from git and have following data.
::

    <?php

    putenv('INSTANCE=local');

    define( 'DB_NAME', '' );
    define( 'DB_USER', '' );
    define( 'DB_PASSWORD', '' );
    define( 'DB_HOST', '' );
    define( 'WP_DEBUG', false );

The INSTANCE should be the same as server name defined in deploy.php.

This file should be included in ``wp-config.php`` before ``require_once(ABSPATH . 'wp-settings.php');``
::

  require_once(ABSPATH . 'wp-config-local.php');

Deployment
----------

The deploy task consist of following tasks:
::

    task('deploy', [
        // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-lock
        'deploy:check_lock',

        // Read more on https://github.com/sourcebroker/deployer-extended#deploy-check-composer-install
        'deploy:check_composer_install',

        // Standard deployer deploy:prepare
        'deploy:prepare',

        // Standard deployer deploy:lock
        'deploy:lock',

        // Standard deployer deploy:release
        'deploy:release',

        // Standard deployer deploy:update_code
        'deploy:update_code',

        // Standard deployer deploy:shared
        'deploy:shared',

        // Standard deployer deploy:writable
        'deploy:writable',

        // Standard deployer deploy:vendors
        'deploy:vendors',

        // Detect WP version and get fresh code from WordPress git repo
        'deploy:wp:core',

        // Standard deployer deploy:copy_dirs. Copy plugins from previous release of WordPress
        'deploy:copy_dirs',

        // Standard deployer deploy:clear_paths
        'deploy:clear_paths',

        // Create database backup, compress and copy to database store.
        // Read more on https://github.com/sourcebroker/deployer-extended-database#db-backup
        'db:backup',

        // Clear php cli cache.
        // Read more on https://github.com/sourcebroker/deployer-extended#php-clear-cache-cli
        'php:clear_cache_cli',

        // Start buffering http requests. No frontend access possible from now.
        // Read more on https://github.com/sourcebroker/deployer-extended#buffer-start
        'buffer:start',

        // Standard deployer symlink (symlink release/x/ to current/)
        'deploy:symlink',

        // Clear frontend http cache.
        // Read more on https://github.com/sourcebroker/deployer-extended#php-clear-cache-http
        'php:clear_cache_http',

        // Frontend access possible again from now
        // Read more on https://github.com/sourcebroker/deployer-extended#buffer-stop
        'buffer:stop',

        // Standard deployer deploy:unlock
        'deploy:unlock',

        // Standard deployer cleanup.
        'cleanup',
    ])->desc('Deploy your WordPress');

Its very advisable that you test deploy on some beta instance first :)
::

   dep deploy beta

The shared dirs are:
::

    set('shared_dirs', [
            'wp-content/uploads',
            'wp-content/languages',
            'wp-content/upgrade',
        ]
    );

The shared files are:
::

    set('shared_files', [
        'wp-config-local.php',
    ]);

Synchronizing database
----------------------

Database synchronization is done with `sourcebroker/deployer-extended-database`.
Example of command for synchronizing database from live to local instance:
::

   dep db:pull live


Domain replacement
++++++++++++++++++

The "post_command" task "db:import:post_command:wp_domains" will change domains declared in "public_urls". Domain
replacement is done with cli command "search-replace" from `wp-cli/wp-cli`_.

Please mind to have the same amount of "public_urls" for each of instances because replacement on domains is done for
every pair of corresponding urls.

Look at following example to give you idea:
::

    server('live', '111.111.111.111')
        ->user('www-data')
        ->set('public_urls', ['https://www.example.com', 'https://sub.example.com'])
        ->set('deploy_path', '/var/www/example.com.live');

    server('beta', '111.111.111.111')
        ->user('www-data')
        ->set('public_urls', ['https://beta.example.com', 'https://beta-sub.example.com'])
        ->set('deploy_path', '/var/www/example.com.beta');

    server('local', 'localhost')
        ->set('public_urls', ['https://example-com.dev', 'https://sub-example-com.dev'])
        ->set('deploy_path', getcwd());


The if you will do:
::

    dep db:pull live

the following commands will be done automatically after database import:
::

    wp search-replace https://www.example.com https://example-com.dev
    wp search-replace https://sub.example.com https://sub-example-com.dev


Configuration
+++++++++++++

Database synchro configuration:
::

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

Mind that "deploy.php" file must be the same on all instance before you can start to do database synchronization.


Synchronizing media & WordPress / plugins code
----------------------------------------------

Media synchronization is done with package `sourcebroker/deployer-extended-media`_.
The command for synchronizing media & php files which are out of git is:
 ::

   dep media:pull live

Because we do not use composer to get WordPress and plugins therefore we will treat here code of WordPress and
plugins as kind of media to synchronize. This is a bit o misuse of `sourcebroker/deployer-extended-media`_ but
if we think of media as part of project which is out of git that needs to be synchronized between instances then
our WordPress and plugins php code which is also out of git is bunch of files that needs to be synchronized
between instances.

Therefore our config to synchronize files media & WordPress / plugins code looks like this:
::

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



.. _sourcebroker/deployer-extended: https://github.com/sourcebroker/deployer-extended
.. _sourcebroker/deployer-extended-media: https://github.com/sourcebroker/deployer-extended-media
.. _sourcebroker/deployer-extended-database: https://github.com/sourcebroker/deployer-extended-database
.. _sourcebroker/deployer-extended-wordpress: https://github.com/sourcebroker/deployer-extended-wordpress
.. _sourcebroker/deployer-extended-wordpress-composer: https://github.com/sourcebroker/deployer-extended-wordpress-composer
.. _wp-cli/search-replace-command: https://github.com/wp-cli/search-replace-command
.. _wp-cli/wp-cli: https://github.com/wp-cli/wp-cli