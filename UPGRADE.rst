
UPGRADE
-------

7 -> 8
~~~~~~

1) ``set('composer_channel', 2);`` is now default. You can remove it from you config.

2) A Deployer 7 specific: in your deploy.php change ``hostname`` to ``setHostname``, change ``user`` to ``setRemoteUser``.

3) It is now required to always put stage name. So before version 8.0 you could do ``dep db:export`` to export the
   database on current host. Now you need to ``dep db:export local`` to export database when you run it on `local` host.