
Changelog
---------

master
~~~~~~

a) [BUGFIX][BREAKING] Rename task names because Windows compatibility.
b) [TASK][BREAKING] Add compatibility with new deployer-instance.
c) [TASK] Do not allow to pull, push, copy, link media and database to live instance.
d) [TASK] Add "export $(cat .env | xargs) " to db:import:post_command:wp_domains to have possibility to set php version.
e) [TASK] Update deployer-extended-media, deployer-extended-database, deployer-instance, deployer-extended.
f) [TASK][BREAKING] By setting ``set('branch_detect_to_deploy', false);`` change the default unsafe bahaviour of deployer to
    deploy the currently checked out up branch. The branch must be set explicitly in host configuration.
g) [TASK][BREAKING] Default config is taken now from config/.env - similar to bedrock.
   Look at https://github.com/sourcebroker/wordpress-starter to get idea how it works.
h) [TASK] Export only PATH from .env because PATH is only needed for working db:import:post_command:wp_domains.
i) [TASK] Add local task to protect vendor folder because its publicly available.

1.0.0
~~~~~

a) [TASK] Deployer 6 compatibility
b) [TASK][BREAKING] Add .htaccess to shared files and add it to files downloaded with 'media:pull'
c) [TASK] Add wp-admin/index.php entrypoint.
d) [TASK] Update dependencies.s

0.6.1
~~~~~

a) [BUGFIX] linux command "paste" is not available on all shells.


0.6.0
~~~~~

a) [TASK][!!!BREAKING] Upgrade to wp-cli 2
b) [TASK][!!!BREAKING] Change the way the wp-cli binary is detected. It must be now present in vendor/bin.

0.5.0
~~~~~

a) [BUGFIX][!!!] Revert full wp-cli/wp-cli.

0.4.0
~~~~~

a) [TASK][!!!] Instead whole wp-cli/wp-cli load only wp-cli/search-replace-command.
b) [TASK] Update deployer-extended-* packages.
c) [FEATURE] Add sourcebroker/deployer-loader
d) [TASK] Add .gitignore with /vendors so vendors can be installed while developing ext.
e) [TASK] Move "db:import:post_command:wp_domains.php" to proper folder.
f) [TASK] Use Loader to load recipes from deployer-extended-*
g) [TASK] Update docs.
h) [TASK] In deploy add task do backup of database with rotation "last 10".
i) [TASK] Docs.

0.3.1
~~~~~

a) [TASK] Add more clear_paths files

0.3.0
~~~~~

a) Add dependency to deployer/dist

0.2.0
~~~~~

a) Update sourcebroker/deployer-extended-database to version 4.0.0
b) Update sourcebroker/deployer-extended to version 6.0.0
