<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/npm.php';

// Config
set('bin/php', 'php8.1');
set('repository', 'https://github.com/SlovakNationalGallery/webumenia.sk.git');

add('shared_files', ['public/sitemap.xml']);
add('shared_dirs', [
    'public/images/autori',
    'public/images/clanky',
    'public/images/diela',
    'public/images/intro',
    'public/images/kolekcie',
    'public/images/uploaded',
    'public/panorama',
    'public/sitemaps',
    'resources/fonts',
]);

// Hosts
host('test')
    ->set('hostname', 'webumenia.sk')
    ->set('remote_user', 'lab_sng')
    ->set('branch', 'develop')
    ->set('deploy_path', '/var/www/test.webumenia.sk');

host('production')
    ->set('hostname', 'webumenia.sk')
    ->set('remote_user', 'lab_sng')
    ->set('branch', 'master')
    ->set('deploy_path', '/var/www/webumenia.sk');

// Tasks
task('build', function () {
    cd('{{release_path}}');
    run('{{bin/npm}} install');
    run('{{bin/npm}} run production');
});

// Override default route:cache task for mcamara/laravel-localization
task('artisan:route:cache', artisan('route:trans:cache'));

after('deploy:vendors', 'build');
after('deploy:failed', 'deploy:unlock');
