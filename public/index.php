<?php

define('PROJECT_ROOT', realpath(__DIR__ . '/..'));
require PROJECT_ROOT . '/src/Config/start.php';

$app = new \Slim\Slim();
$app->get('/', function () {
    echo "Hello";
});
$app->run();