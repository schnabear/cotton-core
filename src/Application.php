<?php

namespace Cotton;

class Application
{
    protected $router;

    public function __construct(Router $router = null)
    {
        if (!$router) {
            $this->router = new Router;
        } else {
            $this->router = $router;
        }
    }

    public function __call($method, $args)
    {
        $method = strtolower($method);
        if (!in_array(strtoupper($method), ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'ANY'], true)) {
            throw new \BadMethodCallException;
        }

        // return call_user_func_array([$this->router, $method], $args);
        return $this->router->$method(...$args);
    }

    public function run()
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $uri = trim($_SERVER['PATH_INFO'],'/');
        return $this->router->execute($method, $uri);
    }
}
