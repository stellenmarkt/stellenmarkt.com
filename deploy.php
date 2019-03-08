<?php
namespace Deployer;

require 'recipe/zend_framework.php';

// Project name
set('application', 'Gastro24');

// Project repository
set('repository', 'https://github.com/gastro24/gastro24.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', [
   'shared',
]);

// Writable dirs by web server 
add('writable_dirs', []);

set('default_stage', 'prod');

// Hosts

host('php7.gastrojob24.ch')
    ->user('yawik')
    ->stage('prod')
    ->multiplexing(false) 
    ->set('deploy_path', '/var/www/production');    
    
// Tasks
task('pwd', function () {
    $result = run('pwd');
    writeln("Current dir: $result");
});

before('deploy:symlink', 'deploy:build');

task('deploy:build', function () {
    run('cd {{release_path}}/test/sandbox && rm -R config var && ln -s ../../../../shared/shared/var/ && ln -s ../../../../shared/shared/config/ && cd public && ln -s ../../../../../shared/test/sandbox/public/.htaccess');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

