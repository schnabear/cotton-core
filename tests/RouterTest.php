<?php

class RouterTest extends PHPUnit_Framework_TestCase
{
    public function testMap()
    {
        $class = new \ReflectionClass('\Cotton\Router');
        $property = $class->getProperty('routes');
        $property->setAccessible(true);

        $router = new \Cotton\Router;
        $router->map(['GET'], '/foo/', function() {
            return true;
        });
        $value = $property->getValue($router);
        $this->assertTrue(in_array('GET', array_keys($value)));
    }

    public function testAny()
    {
        $router = new \Cotton\Router;
        $router->any('/foo/', function() {
            return true;
        });
        $output = $router->execute('GET', 'foo');
        $this->assertTrue($output);
        $output = $router->execute('POST', 'foo');
        $this->assertTrue($output);
        $output = $router->execute('PUT', 'foo');
        $this->assertTrue($output);
        $output = $router->execute('PATCH', 'foo');
        $this->assertTrue($output);
        $output = $router->execute('DELETE', 'foo');
        $this->assertTrue($output);
        $output = $router->execute('FOO', 'foo');
        $this->assertFalse($output);
    }

    public function testGet()
    {
        $router = new \Cotton\Router;
        $router->get('/foo/', function() {
            return true;
        });
        $output = $router->execute('GET', 'foo');
        $this->assertTrue($output);
        $output = $router->execute('POST', 'foo');
        $this->assertFalse($output);
        $output = $router->execute('FOO', 'foo');
        $this->assertFalse($output);
    }

    public function testPost()
    {
        $router = new \Cotton\Router;
        $router->post('/foo/', function() {
            return true;
        });
        $output = $router->execute('POST', 'foo');
        $this->assertTrue($output);
        $output = $router->execute('GET', 'foo');
        $this->assertFalse($output);
        $output = $router->execute('FOO', 'foo');
        $this->assertFalse($output);
    }

    public function testPut()
    {
        $router = new \Cotton\Router;
        $router->put('/foo/', function() {
            return true;
        });
        $output = $router->execute('PUT', 'foo');
        $this->assertTrue($output);
        $output = $router->execute('POST', 'foo');
        $this->assertFalse($output);
        $output = $router->execute('FOO', 'foo');
        $this->assertFalse($output);
    }

    public function testPatch()
    {
        $router = new \Cotton\Router;
        $router->patch('/foo/', function() {
            return true;
        });
        $output = $router->execute('PATCH', 'foo');
        $this->assertTrue($output);
        $output = $router->execute('POST', 'foo');
        $this->assertFalse($output);
        $output = $router->execute('FOO', 'foo');
        $this->assertFalse($output);
    }

    public function testDelete()
    {
        $router = new \Cotton\Router;
        $router->delete('/foo/', function() {
            return true;
        });
        $output = $router->execute('DELETE', 'foo');
        $this->assertTrue($output);
        $output = $router->execute('POST', 'foo');
        $this->assertFalse($output);
        $output = $router->execute('FOO', 'foo');
        $this->assertFalse($output);
    }
}
