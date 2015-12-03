<?php
namespace test\Helpers;
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit_Framework_TestCase;
use Slim\Environment;


abstract class SlimTest extends PHPUnit_Framework_TestCase
{
    public function request($method, $path, $options = array())
    {
        // Capture STDOUT
        ob_start();

        // Prepare a mock environment
        Environment::mock(array_merge(array(
            'REQUEST_METHOD' => $method,
            'PATH_INFO' => $path,
            'SERVER_NAME' => 'slim-test.dev',
        ), $options));

        $app = new \Slim\Slim();
        $this->app = $app;
        $this->request = $app->request();
        $this->response = $app->response();

        // Return STDOUT
        return ob_get_clean();
    }

    public function get($path, $options = array())
    {
        $this->request('GET', $path, $options);
    }
}
