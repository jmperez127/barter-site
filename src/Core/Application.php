<?php

namespace Barter\Core;

define('SRC_PATH', realpath(__DIR__ . '/../'));
require realpath(SRC_PATH . '/Config/start.php');
use Symfony\Component\Yaml\Parser;

class Application {
    protected $slim_instance;

    public function __construct($configArr) {
        $yaml_parser = new Parser();
        $config = $yaml_parser->parse(file_get_contents(realpath(SRC_PATH . '/Config/application.yml')));
        $this->slim_instance = new \Slim\Slim($config);

    }

    public function request() {
        $this->slim_instance->request();
    }

    public function response() {
        return $this->slim_instance->response();
    }

    public function invoke(){
        $test = $this->slim_instance;
//        $test->middleware[0]->call();
        $test->response()->finalize();
        return $test->response();
    }

}

//
//// Load application config
//$config["log.writer"] = new Barter\Config\LogWriter(); //Additional default config
//
//// Initialize app and config
//$app = new \Slim\Slim($config);
//$app->setName("Barter site");
//
//// Set views path
//$views_path = $app->config('templates.path');
//if ($views_path != "./templates") {
//    $app->config('templates.path', realpath(SRC_PATH . "/" . $views_path));
//    $views_path = $app->config('templates.path');
//}
//
//// Load additional environment variables
//$env_config = $yaml_parser->parse(
//    file_get_contents(SRC_PATH . '/Config/Environments/' . $app->config('mode') . '.yml'));
//
//$app->config($env_config);
//
//// Only invoked if mode is "production"
//$app->configureMode('production', function () use ($app, $yaml_parser) {
//
//});
//
//// Only invoked if mode is "development"
//$app->configureMode('development', function () use ($app) {
//
//});
//
//
//// Routes
//$app->get('/', function () use ($app) {
//    $app->render("index.php");
//});
//
//$app->get('/items', function () {
//});
//
//$app->get('/items/:id', function ($id) {
//});
//
//$app->post('/items', function () {
//});
//
//$app->put('/items/:id', function () {
//});
//
//$app->delete('/items/:id', function () {
//});
//
//$app->run();