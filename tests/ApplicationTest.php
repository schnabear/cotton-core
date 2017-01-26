<?php

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $this->expectException(\BadMethodCallException::class);
        $app = new \Cotton\Application;
        $app->foo();
    }

    public function testRun()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['PATH_INFO'] = '/';
        $app = new \Cotton\Application;
        $app->get('/^$/', function() {
            return true;
        });
        $this->assertTrue($app->run());

        $_SERVER['PATH_INFO'] = '';
        $this->assertTrue($app->run());

        $_SERVER['PATH_INFO'] = '404';
        $this->assertFalse($app->run());

        $app = new \Cotton\Application;
        $app->get('/.+/', function() {
            return true;
        });
        $this->assertTrue($app->run());

        $_SERVER['PATH_INFO'] = 'foo/bar';
        $app = new \Cotton\Application;
        $app->get('/foo\/(.+)/', function($foo) {
            return $foo;
        });
        $this->assertEquals($app->run(), 'bar');

        $_SERVER['PATH_INFO'] = 'foo/bar/baz';
        $app = new \Cotton\Application;
        $app->get('/foo\/(.+)\/(.+)/', function($foobar, $foobaz) {
            return "{$foobar}{$foobaz}";
        });
        $this->assertEquals($app->run(), 'barbaz');

        $_SERVER['PATH_INFO'] = '';
        $app = new \Cotton\Application;
        $app->get('/^$/', function() {
            echo 'HELLO';
        });
        ob_start();
        $app->run();
        $output = ob_get_clean();
        $this->assertEquals('HELLO', $output);

        $_SERVER['PATH_INFO'] = 'call/maybe';
        $app = new \Cotton\Application;
        $app->get('/call\/(.+)/', '\ExampleController@call');
        $this->assertEquals($app->run(), 'maybe');
    }
}

class ExampleController extends \Cotton\Controller
{
    public function call($value)
    {
        return $value;
    }
}
