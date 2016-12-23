<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'    => 'Mysql',
        'host'       => 'localhost',
        'username'   => 'root',
        'password'   => 'psp',
        'dbname'     => 'hotel_manager',
        'charset'    => 'utf8',
    ],

    'application' => [
        'modelsDir'      => APP_PATH . '/models/',
        'utilsDir'       => APP_PATH . '/utils/',
        'controllersDir' => APP_PATH . '/controllers/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'baseUri'        => '/hotel-manager/',
        'jwtKey'	 => '8d9081c219acf736bfbbce08c0c5770ef3725e378d4440248cb6d0c0a976de31109dddf1a08c9417933edb31a3bd7cd67562fa154dce97757da0678b3a69e59a'
    ]
]);
