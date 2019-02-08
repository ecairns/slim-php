<?php

use \CCB\Services\FilmService;
use \CCB\Controllers\Api\FilmController;

// DIC configuration
$container = $app->getContainer();

/**
 * Create a single instance of a class
 * @param       $class
 * @param array $params
 * @return mixed
 */
function singleton($class, array $params = []) {
    static $instance = [];
    if (is_null($instance[$class])) {
        $instance[$class] = new $class(...$params); //Argument unpacking
    }

    return $instance[$class];
}

$container['db'] = function ($c) {
    $db = $c['settings']['db'];

    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['database'], $db['username'], $db['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
};

// Database
$container['capsule'] = function ($c) {
    $capsule = new Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($c['settings']['db']);
    return $capsule;
};

// Dependency Injections
$container['FilmService'] = function ($c) {
    return singleton(FilmService::class, [$c['settings']['search']]);
};

$container['MovieApiController'] = function ($c) {
    return new FilmController($c['FilmService']);
};
