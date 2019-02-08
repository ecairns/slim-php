<?php

use \CCB\Controllers\API\FilmController;

// Make file paths relative to project root
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$settings = require 'app/config.php';
$app      = new \Slim\App($settings);

require 'app/src/dependencies.php';

$container = $app->getContainer();

$app->get('/movies',  "MovieApiController:search");

// Register the database connection with Eloquent
$capsule = $app->getContainer()->get('capsule');
$capsule->bootEloquent();

$app->run();

