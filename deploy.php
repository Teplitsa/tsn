<?php
namespace Deployer;
require 'recipe/laravel.php';

// Configuration

set('repository', 'git@gitlab.com:spyric/ZHKHack.git');
set('http_user', 'gitlab-runner');
set('http_group', 'gitlab-runner');
set('writable_mode', 'chown');

add('shared_files', []);
add('shared_dirs', [
    'public/upload',
    'public/storage'
]);

add('writable_dirs', ['public']);

// Servers

server('production', 'tsn.ananas-web.ru')
    ->user('root')
    ->identityFile('storage/key.pub', 'storage/key')
    ->set('deploy_path', '/web/tsn_ananas-web_ru');

server('staging', 'tsn.dev.ananas-web.ru')
    ->user('root')
    ->identityFile('storage/key.pub', 'storage/key')
    ->stage('staging')
    ->set('deploy_path', '/web/tsn_dev_ananas-web_ru');


// Tasks

desc('Restart PHP-FPM service');
task('php-fpm:restart', function () {
    // The user must have rights for restart service
    // /etc/sudoers: username ALL=NOPASSWD:/bin/systemctl restart php-fpm.service
    run('sudo /bin/systemctl restart php7.0-fpm');
});


/**
 * Restart php-fpm on success deploy.
 */
task('artisan:queue:restart', function () {
    run('php {{deploy_path}}/current/artisan queue:restart');
})->desc('Restart queue');
/**
 * Restart php-fpm on success deploy.
 */
task('yarn', function () {
    run('cd {{release_path}} && yarn');
})->desc('Build scripts');

task('npm:run', function () {
    $output = run('cd {{release_path}} && npm run prod');
    writeln('<info>' . $output . '</info>');
})->desc('Build scripts');

task('deploy:migrate', function () {
    $output = run('{{bin/php}} {{release_path}}/artisan migrate --force');
    writeln('<info>' . $output . '</info>');
})->desc('Migrate DB');

task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:migrate',
    'yarn',
    'npm:run',
    'artisan:view:clear',
    'artisan:cache:clear',
    'artisan:config:cache',
    'artisan:optimize',
    'deploy:writable',
    'deploy:symlink',
    'deploy:unlock',
    'artisan:queue:restart',
    'php-fpm:restart',
    'cleanup',
])->desc('Deploy your project');