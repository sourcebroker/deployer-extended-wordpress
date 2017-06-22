deployer-extended-wordpress
===========================

.. contents:: :local:

What does it do?
----------------

NOTE!!! This is early phase version. Use on you own risk.


This package provides deploy task for deploying WordPress with deployer (deployer.org).

This "deploy" task depends on:

- `sourcebroker/deployer-extended`_ package which provides some deployer tasks that can be used for any framework or CMS

Additionally this package depends on two more packages that are not used directly for deploy but are useful
to database and media synchronization:

- `sourcebroker/deployer-extended-database`_ package which provides some php framework independent tasks
  to synchronize database

- `sourcebroker/deployer-extended-media`_  package which provides some php framework independent tasks
  to synchronize media


Installation
------------

1) Install package with composer:
::

      composer require sourcebroker/deployer-extended-wordpress ~0.0.1


2) If you are using deployer as composer package then just put following line in your deploy.php:
   ::

      new \SourceBroker\DeployerExtendedWordpress\Loader();

3) If you are using deployer as phar then put following lines in your deploy.php:
   ::

      require __DIR__ . '/vendor/autoload.php';
      new \SourceBroker\DeployerExtendedWordpress\Loader();

4) Remove task "deploy" from your deploy.php. Otherwise you will overwrite deploy task defined in
   deployer/deploy/task/deploy.php





.. _sourcebroker/deployer-extended: https://github.com/sourcebroker/deployer-extended
.. _sourcebroker/deployer-extended-media: https://github.com/sourcebroker/deployer-extended-media
.. _sourcebroker/deployer-extended-database: https://github.com/sourcebroker/deployer-extended-database
