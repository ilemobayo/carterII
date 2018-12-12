<?php
header("Content-Type: application/json; charset=UTF-8");

require 'vendor/autoload.php';
require 'plugin/keygen.php';
require 'plugin/jwt.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = 'localhost';
$config['db']['user']   = 'root';
$config['db']['pass']   = '';
$config['db']['dbname'] = 'orin_hub';

$app = new \Slim\App(['settings' => $config]);

session_cache_limiter(false);

$container = $app->getContainer();
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};

// PDO database library 
// $container['db'] = function ($c) {
//     $settings = $c->get('settings')['db'];
//     $pdo = new PDO("mysql:host=" . $settings['host'] . ";dbname=" . $settings['dbname'],
//         $settings['user'], $settings['pass']);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
//     return $pdo;
// };

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],$db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};


//includes for configurations
require_once 'plugin/includes/db_config.php';
require_once 'plugin/includes/errorCodes.php';
require_once 'plugin/includes/functions.php';


require_once 'controller/controller.php';

$app->contentType('application/json');

$app->run();