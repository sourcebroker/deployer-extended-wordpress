<?php

namespace Deployer;

/**
 * Deploys WordPress core to the new release.
 * WordPress core version number is taken from the previous release.
 */
task('deploy:wp:core', function() {
    $wpRepoDir = get('wp_core_dir', '{{deploy_path}}/shared/wordpress.git');
    $wpRepoUrl = get('wp_core_repository', 'https://github.com/WordPress/WordPress.git');

    // Check if {{deploy_path}}/release link exists. if not - it means that task was executed after "deploy:symlink" task, so we can't proceed,
    // because we don't know the path to the previous release
    if (!test("[ -h '{{deploy_path}}/release' ]")) {
        throw new \RuntimeException('Task "deploy:wp:core" can not be called after "deploy:symlink" [Error code: 1827354672]');
    }

    // Check if {{deploy_path}}/current link exists.
    if (!test("[ -h '{{deploy_path}}/current' ]")) {
        // Previous release does not exist - get version from user.
        writeln('<info>Symlink "current" does not exists, so it looks that this is the initial deploy. That means it is not possible to find out wordpress version from previous release and you need to enter version to install manually.</info>');
        $currentWordpressVersion = ask('<info>Please enter a git tag for appropriate WordPress version, e.g. "4.5.3":</info>');
    } else {
        // Previous release exists - get version from previous version.php file.
        $currentWordpressVersion  = run('cat {{deploy_path}}/current/wp-includes/version.php | grep -Ei "wp_version\s+=\s+[\'\"]([0-9\.]+)[\'\"]" | grep -Eo "[0-9\.]" | paste -sd ""')->toString();
    }

    if (!$currentWordpressVersion) {
        writeln('<error>Could not find out which WordPress core version should be installed, you need to copy it to the release directory manually.</error>');
        return;
    }

    // Ensure wordpress git directory exists.
    run("if [ ! -d $wpRepoDir ]; then mkdir -p $wpRepoDir; fi");

    // Download latest version of WP git into shared/wordpress.git
    run("if [ -e $wpRepoDir/.git ]; then cd $wpRepoDir && git checkout master && git fetch --all && git fetch --tags; else cd $wpRepoDir && git clone $wpRepoUrl .;fi");

    // Checkout to the appropriate version of WP.
    run("cd $wpRepoDir && git checkout tags/$currentWordpressVersion");

    // Copy WP core to new release folder (without .git directory and without overwriting existing files)
    run("rsync -av --exclude '.git' --ignore-existing {{deploy_path}}/shared/wordpress.git/ {{deploy_path}}/release/");
})->desc('Installing WordPress core');
