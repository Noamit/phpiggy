<?php

declare(strict_types=1);

namespace Framework;

class Router
{
    private array $routes = [];
    private array $middlewares = [];
    public function add(string $method, string $path, array $controller)
    {

        $path = $this->normalizePath($path);
        $this->routes[] = [
            'path' => $path,
            'method' => strtoupper($method),
            'controller' => $controller,
            'middlewares' => []
        ];
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path, '/');
        $path = "/{$path}/";
        $path = preg_replace('#[/]{2,}#', '/', $path);
        return $path;
    }

    public function dispatch(string $path, string $method, Container $container = null)
    {
        $path = $this->normalizePath($path);
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if (!preg_match("#^{$route['path']}$#", $path) || $route['method'] !== $method) {
                continue;
            }

            [$class, $func] = $route['controller'];
            $controllerInstance = $container ? $container->resolve($class) : new $class;

            $action = fn () => $controllerInstance->$func(); 

            //...$this->middlewares is last , the oder is matter. global middleware run first
            $allMiddleware = [...$route['middlewares'], ...$this->middlewares];

            //before $allMiddleware , the loop was only on $this->middlewares. but we added a private middlewares
            foreach ($allMiddleware as $middleware) {
                $middlewareInstance = $container ? $container->resolve($middleware) : new $middleware;
                $action = fn () => $middlewareInstance->process($action);
            }

            $action();
            // $controllerInstance->{$func}();
            return;
        }
    }

    public function addMiddleware(string $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function addRouteMiddleware(string $middleware)
    {
        $lastRouteKey = array_key_last($this->routes);
        $this->routes[$lastRouteKey]['middlewares'][] = $middleware;
    }
}