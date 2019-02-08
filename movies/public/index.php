<?php
// Make file paths relative to project root
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$settings = require 'app/config.php';
$app      = new \Slim\App($settings);

require 'app/src/dependencies.php';
require 'app/src/routes.php';

// Register Eloquent
$capsule = $app->getContainer()->get('capsule');
$capsule->bootEloquent();

$app->run();

