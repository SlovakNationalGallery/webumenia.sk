<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default deployment strategy
    |--------------------------------------------------------------------------
    |
    | This option defines which deployment strategy to use by default on all
    | of your hosts. Laravel Deployer provides some strategies out-of-box
    | for you to choose from explained in detail in the documentation.
    |
    | Supported: 'basic', 'firstdeploy', 'local', 'pull'.
    |
    */

    'default' => 'basic',

    /*
    |--------------------------------------------------------------------------
    | Custom deployment strategies
    |--------------------------------------------------------------------------
    |
    | Here, you can easily set up new custom strategies as a list of tasks.
    | Any key of this array are supported in the `default` option above.
    | Any key matching Laravel Deployer's strategies overrides them.
    |
    */

    'strategies' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Hooks
    |--------------------------------------------------------------------------
    |
    | Hooks let you customize your deployments conveniently by pushing tasks
    | into strategic places of your deployment flow. Each of the official
    | strategies invoke hooks in different ways to implement their logic.
    |
    */

    'hooks' => [
        // Right before we start deploying.
        'start' => [
            //
        ],

        // Code and composer vendors are ready but nothing is built.
        'build' => [
            'npm:install',
            'npm:production',
        ],

        // Deployment is done but not live yet (before symlink)
        'ready' => [
            'artisan:storage:link',
            'artisan:view:clear',
            'artisan:cache:clear',
            'artisan:config:cache',
            'artisan:migrate',
            'artisan:queue:restart',
        ],

        // Deployment is done and live
        'done' => [
            'fpm:reload',
        ],

        // Deployment succeeded.
        'success' => [
            //
        ],

        // Deployment failed.
        'fail' => [
            //
        ],

        // After a deployment has been rolled back.
        'rollback' => [
            'fpm:reload',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Deployment options
    |--------------------------------------------------------------------------
    |
    | Options follow a simple key/value structure and are used within tasks
    | to make them more configurable and reusable. You can use options to
    | configure existing tasks or to use within your own custom tasks.
    |
    */

    'options' => [
        'application' => env('APP_NAME', 'Laravel'),
        'repository' => 'https://github.com/SlovakNationalGallery/webumenia.sk.git',
        'http_user' => 'www-data',
        'php_fpm_service' => 'php7.2-fpm',
        'shared_dirs' => [
            'public/images/autori',
            'public/images/clanky',
            'public/images/diela',
            'public/images/intro',
            'public/images/kolekcie',
            'public/images/uploaded',
            'public/panorama',
            'public/sitemaps',
            'storage',
            'resources/fonts',
        ],
        'shared_files' => [
            '.env',
            'public/sitemap.xml',
        ],
        'writable_dirs' => [
            'bootstrap/cache',
            'storage',
            'storage/app',
            'storage/app/public',
            'storage/framework',
            'storage/framework/cache',
            'storage/framework/sessions',
            'storage/framework/views',
            'storage/logs',
            'public/images/autori',
            'public/images/clanky',
            'public/images/diela',
            'public/images/intro',
            'public/images/kolekcie',
            'public/images/uploaded',
            'public/sitemaps',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Hosts
    |--------------------------------------------------------------------------
    |
    | Here, you can define any domain or subdomain you want to deploy to.
    | You can provide them with roles and stages to filter them during
    | deployment. Read more about how to configure them in the docs.
    |
    */

    'hosts' => [
        'production' => [
            'hostname' => 'webumenia.sk',
            'deploy_path' => '/var/www/webumenia.sk',
            'user' => 'lab_sng',
            'branch' => 'master'
        ],
        'test' => [
            'hostname' => 'webumenia.sk',
            'deploy_path' => '/var/www/test.webumenia.sk',
            'user' => 'lab_sng',
            'branch' => 'develop'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Localhost
    |--------------------------------------------------------------------------
    |
    | This localhost option give you the ability to deploy directly on your
    | local machine, without needing any SSH connection. You can use the
    | same configurations used by hosts to configure your localhost.
    |
    */

    'localhost' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Include additional Deployer recipes
    |--------------------------------------------------------------------------
    |
    | Here, you can add any third party recipes to provide additional tasks,
    | options and strategies. Therefore, it also allows you to create and
    | include your own recipes to define more complex deployment flows.
    |
    */

    'include' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Use a custom Deployer file
    |--------------------------------------------------------------------------
    |
    | If you know what you are doing and want to take complete control over
    | Deployer's file, you can provide its path here. Note that, without
    | this configuration file, the root's deployer file will be used.
    |
    */

    'custom_deployer_file' => false,

];
