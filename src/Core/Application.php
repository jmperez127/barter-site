<?php

namespace Barter\Core;

define('SRC_PATH', realpath(__DIR__ . '/../'));
require realpath(SRC_PATH . '/Config/start.php');

use Slim\Slim;
use Symfony\Component\Yaml\Parser;

class Application {
    protected $slim_instance;
    protected $config;
    protected $yaml_parser;

    public function __construct() {
        $this->yaml_parser = new Parser();
        $this->loadConfigFromYml();
        $this->slim_instance = new Slim($this->config);
        $this->setAppName();
        $this->setViewsPath();
        $this->loadEnvironmentsConfigFromYml();

    }

    private function setAppName() {
        if (isset($this->config["app.name"]))
            $this->slim_instance->setName($this->config["app.name"]);
    }

    public function getSlimInstance() {
        return $this->slim_instance;
    }

    private function setViewsPath() {
        $views_path = $this->slim_instance->config('templates.path');
        if ($views_path != "./templates") {
            $this->slim_instance->config('templates.path', realpath(SRC_PATH . "/" . $views_path));
            $views_path = $this->slim_instance->config('templates.path');
        }
    }

    private function loadConfigFromYml() {
        $this->config = $this->yaml_parser->parse(file_get_contents(realpath(SRC_PATH . '/Config/application.yml')));
    }

    private function loadEnvironmentsConfigFromYml() {
        $env_config = $this->yaml_parser->parse(
            file_get_contents(SRC_PATH . '/Config/Environments/' . $this->slim_instance->config('mode') . '.yml'));

        $this->slim_instance->config($env_config);
        $app = $this->slim_instance;
        $this->slim_instance->configureMode('production', function () use ($app) {
            //code to run only in production
        });

        $this->slim_instance->configureMode('development', function () use ($app) {
            //code to run only in development
        });
    }


}
