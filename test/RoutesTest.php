<?php
namespace test;
require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Environment;
use test\Helpers\SlimTest;


class RoutesTest extends SlimTest
{
    public function testIndex()
    {
        $this->get('/');
        $this->assertEquals('200', $this->response->status());
    }


}
