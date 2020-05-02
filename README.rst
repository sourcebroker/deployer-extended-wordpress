deployer-extended-wordpress
===========================

.. contents:: :local:

What does it do?
----------------

This package provides deploy task for deploying WordPress with deployer (deployer.org) and additionally a tasks
to synchronize database and media files.

The deployment is simplified in order to have ability to auto-upgrade WordPress and upgrade plugins
manually by admin panel (or automatically with tools like InfiniteWP). This is a half way between
no deployment at all and deployment fully driven by composer. If you want to manage WordPress and plugins
fully with composer then check https://roots.io/ and `sourcebroker/deployer-extended-wordpress-composer`_.


Should I use "deployer-extended-wordpress" or "deployer-extended-wordpress-composer"?
-------------------------------------------------------------------------------------

In `sourcebroker/deployer-extended-wordpress`_ the WordPress and third party plugins are installed manually. What you
have in git is basically only your theme. The good thing is that in such case you can update WordPress and plugins
automatically. This can be considered as preferable for low budget WordPress websites.

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

      host('live')
          ->hostname('example.com')->port(22)
          ->user('deploy')
          ->set('public_urls', ['https://www.example.com/'])
          ->set('deploy_path', '/var/www/example.com/live');

      host('beta', '111.111.111.111')
          ->hostname('example.com')->port(22)
          ->user('deploy')
          ->set('public_urls', ['https://beta.example.com/'])
          ->set('deploy_path', '/var/www/example.com/beta');

      host('local')
          ->set('public_urls', ['https://example-com.dev/'])
          ->set('deploy_path', getcwd());


Mind the declaration of host('local'); Its needed for database tasks to declare domain replacements,
and path to store database dumps.

Project's folders structure
---------------------------

This deployment has following assumptions:

1) WordPress source code is not in GIT in order to have ability to easily upgrade them from admin panel.
2) ``wp-content/plugins`` should be most out of git to in order to have ability to easily upgrade them from admin panel.
   You can have however some plugins in GIT if you like.
3) ``wp-content/mu-plugins`` can be partially out of git but you can also have plugins there which are in git.
4) ``config/environments`` and use of ``wp-config`` and ``.env`` idea is back ported from bedrock
4) Taking the two above points into consideration the only files in GIT will be:
   ::

        /config/environments/development.php
        /config/environments/staging.php
        /config/application.php
        /config/.env
        /wp-content/plugins/my-plugin-in-git
        /wp-content/mu-plugins/my-mu-plugin.php
        /wp-content/themes/my-theme/
        .gitignore
        deploy.php
        composer.lock
        composer.json
        wp-config.php

Look at `sourcebroker/wordpress-starter`_ for example how you can use in your WordPress.

.env
++++
As in bedrock the config/.env files is like
::

  DB_NAME='database_name'
  DB_USER='database_user'
  DB_PASSWORD='database_password'

  # Optionally, you can use a data source name (DSN)
  # When using a DSN, you can remove the DB_NAME, DB_USER, DB_PASSWORD, and DB_HOST variables
  # DATABASE_URL='mysql://database_user:database_password@database_host:database_port/database_name'

  # Optional variables
  # DB_HOST='localhost'
  # DB_PREFIX='wp_'

  WP_ENV='development'
  WP_HOME='http://example.com'
  WP_DEBUG_LOG=/path/to/debug.log

  # Generate your keys here: https://roots.io/salts.html
  AUTH_KEY='generateme'
  SECURE_AUTH_KEY='generateme'
  LOGGED_IN_KEY='generateme'
  NONCE_KEY='generateme'
  AUTH_SALT='generateme'
  SECURE_AUTH_SALT='generateme'
  LOGGED_IN_SALT='generateme'
  NONCE_SALT='generateme'

The WP_ENV should be the same as server name defined in deploy.php.


The shared dirs defined in ``deployer/set.php`` are:
::

    set('shared_dirs', [
            'wp-content/uploads',
            'wp-content/languages',
            'wp-content/upgrade',
        ]
    );

The shared files defined in ``deployer/set.php``are:
::

    set('shared_files', [
        '.htaccess',
        'config/.env',
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

    host('live', '111.111.111.111')
        ->hostname('example.com')->port(22)
        ->user('deploy')
        ->set('public_urls', ['https://www.example.com', 'https://sub.example.com'])
        ->set('deploy_path', '/var/www/example.com.live');

    host('beta', '111.111.111.111')
        ->hostname('example.com')->port(22)
        ->user('deploy')
        ->set('public_urls', ['https://beta.example.com', 'https://beta-sub.example.com'])
        ->set('deploy_path', '/var/www/example.com.beta');

    host('local')
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



.. _sourcebroker/deployer-extended: https://github.com/sourcebroker/deployer-extended
.. _sourcebroker/deployer-extended-media: https://github.com/sourcebroker/deployer-extended-media
.. _sourcebroker/deployer-extended-database: https://github.com/sourcebroker/deployer-extended-database
.. _sourcebroker/deployer-extended-wordpress: https://github.com/sourcebroker/deployer-extended-wordpress
.. _sourcebroker/deployer-extended-wordpress-starter: https://github.com/sourcebroker/deployer-extended-wordpress-starter
.. _sourcebroker/deployer-extended-wordpress-composer: https://github.com/sourcebroker/deployer-extended-wordpress-composer
.. _wp-cli/search-replace-command: https://github.com/wp-cli/search-replace-command
.. _wp-cli/wp-cli: https://github.com/wp-cli/wp-cli
