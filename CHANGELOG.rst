
Changelog
---------

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
