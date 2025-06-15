<?php
namespace app\core;

class Router
{
    protected $routes = [];
    protected $db;

    public function __construct($db = null)
    {
        $this->db = $db;
    }

    public function add(string $method, string $route, $callback)
    {
        $method = strtoupper($method);
        $this->routes[$method][$route] = $callback;
    }

    public function get(string $route, $callback)
    {
        $this->add('GET', $route, $callback);
    }

    public function post(string $route, $callback)
    {
        $this->add('POST', $route, $callback);
    }

    public function dispatch(string $method, string $uri)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $method = strtoupper($method);

        if (isset($this->routes[$method][$uri])) {
            $callback = $this->routes[$method][$uri];
            if (is_callable($callback)) {
                return call_user_func($callback);
            } elseif (is_string($callback)) {
                // Формат: "Controller@method"
                [$controllerName, $methodName] = explode('@', $callback);
                $controllerClass = '\\app\\controllers\\' . $controllerName;
                $controller = new $controllerClass($this->db);
                return $controller->$methodName();
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
