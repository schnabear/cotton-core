<?php

namespace Cotton;

class Router
{
    protected $routes = [];

    public function map(array $methods, $pattern, $callback)
    {
        foreach ($methods as $method) {
            if (is_array($pattern)) {
                foreach ($pattern as $value) {
                    $this->routes[$method][$value] = $callback;
                }
            } else {
                $this->routes[$method][$pattern] = $callback;
            }
        }
    }

    public function any($pattern, $callback)
    {
        $this->map(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], $pattern, $callback);
    }

    public function get($pattern, $callback)
    {
        $this->map(['GET'], $pattern, $callback);
    }

    public function post($pattern, $callback)
    {
        $this->map(['POST'], $pattern, $callback);
    }

    public function put($pattern, $callback)
    {
        $this->map(['PUT'], $pattern, $callback);
    }

    public function patch($pattern, $callback)
    {
        $this->map(['PATCH'], $pattern, $callback);
    }

    public function delete($pattern, $callback)
    {
        $this->map(['DELETE'], $pattern, $callback);
    }

    public function execute($method, $uri)
    {
        $routes = isset($this->routes[$method]) ? $this->routes[$method] : [];
        foreach ($routes as $pattern => $callback) {
            if (preg_match($pattern, $uri, $params) === 1) {
                array_shift($params);
                if (is_string($callback)) {
                    list($class, $method) = explode('@', $callback);
                    // return call_user_func_array([(new $class), $method], array_values($params));
                    return (new $class)->$method(...$params);
                } else {
                    // return call_user_func_array($callback, array_values($params));
                    return $callback(...$params);
                }
            }
        }
        return false;
    }
}
