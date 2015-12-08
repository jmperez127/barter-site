<?php

namespace Barter\Core;

require 'start.php';

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Slim\Slim;
use Symfony\Component\Yaml\Parser;

class Application {
    protected $slim_instance;
    protected $config;
    protected $yaml_parser;

    public function __construct() {
        $this->yaml_parser = new Parser();
        $this->loadAppConfig();
        $this->initSlimAppInstance();
        $this->setViewsFolderPath();
        $this->loadAdditionalEnvironmentsConfig();
    }

    private function initSlimAppInstance() {
        $this->slim_instance = new Slim($this->config);

        if (isset($this->config["app.name"]))
            $this->slim_instance->setName($this->config["app.name"]);
    }

    public function getSlimInstance() {
        return $this->slim_instance;
    }

    private function setViewsFolderPath() {
        $views_path = $this->slim_instance->config('templates.path');
        if ($views_path != "./templates") {
            $this->slim_instance->config('templates.path', realpath(SRC_PATH . "/" . $views_path));
            $views_path = $this->slim_instance->config('templates.path');
        }
    }

    private function loadAppConfig() {
        $this->config = $this->yaml_parser->parse(file_get_contents(realpath(SRC_PATH . '/Config/application.yml')));
    }

    private function loadAdditionalEnvironmentsConfig() {
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

    public function run(){
        $this->hookRoutesAndControllers();
        $this->slim_instance->run();
    }

    private function hookRoutesAndControllers() {
        $json = file_get_contents(realpath(SRC_PATH . "/Config/routes.json"));

        $jsonIterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator(json_decode($json, TRUE)),
            RecursiveIteratorIterator::SELF_FIRST);

        foreach ($jsonIterator as $key => $val) {
            if(is_array($val)) {
                echo "$key:\n";
            } else {
                echo "$key => $val\n";
            }
        }


        $app = $this->slim_instance;
        $app->get('/', function () use ($app) {
            $app->render("index.php");
        });
    }

}
