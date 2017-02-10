<?php

// All Deployer recipes are based on `recipe/common.php`.
require 'recipe/laravel.php';

server('dev', '52.169.117.6')
    ->user('gitlab-runner')
    ->identityFile('key.pub', 'key')
    ->stage('dev')
    ->env('deploy_path', '/web/tsn_dev_ananas-web_ru')
    ->env('httpUser', 'gitlab-runner')
;

set('repository', 'git@gitlab.com:spyric/ZHKHack.git');