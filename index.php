<?php

declare(strict_types=1);

session_start();

define('BASE_PATH', __DIR__);

spl_autoload_register(function (string $class): void {
    $path = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    $path = preg_replace('/^' . preg_quote(BASE_PATH . '/', '/') . 'Api/', BASE_PATH . '/api/', $path);
    if (file_exists($path)) {
        require_once $path;
    }
});

$config = require BASE_PATH . '/config/app.php';
date_default_timezone_set($config['timezone']);

$dbConfig = require BASE_PATH . '/config/database.php';

$db = new Api\Core\Database($dbConfig);
$auth = new Api\Core\Auth($db);
$router = new Api\Core\Router($db, $auth);

require BASE_PATH . '/config/routes.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);