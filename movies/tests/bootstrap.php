<?php
use PHPUnit\Framework\TestCase;

// Settings to make all errors more obvious during testing
error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('UTC');

define('PROJECT_ROOT', realpath(__DIR__ . '/..'));

require_once PROJECT_ROOT . '/vendor/autoload.php';

// Initialize our own copy of the slim application
class SlimTestCase extends TestCase {
    public function setUp() {
        $this->initDb();
    }

    public function initDb() {
        $settings = require __DIR__ . "/config.php";

        $capsule = new Illuminate\Database\Capsule\Manager;
        $capsule->addConnection($settings['settings']['db']);
        $capsule->bootEloquent();
    }

    public function getSlimInstance() {
        $app = new \Slim\Slim(array(
            'version'        => '0.0.0',
            'debug'          => false,
            'mode'           => 'testing',
        ));

        // Include our core application file
        require PROJECT_ROOT . '/public/index.php';
        return $app;
    }
};

/* End of file bootstrap.php */