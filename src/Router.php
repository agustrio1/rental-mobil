<?php

namespace App;

class Router
{
    private array $routes = [];
    private array $middlewares = [];
    private string $prefix = '';

    public function get(string $path, callable|array $handler): static
    {
        $this->addRoute('GET', $this->prefix . $path, $handler);
        return $this;
    }

    public function post(string $path, callable|array $handler): static
    {
        $this->addRoute('POST', $this->prefix . $path, $handler);
        return $this;
    }

    public function group(string $prefix, callable $callback, array $middlewares = []): void
    {
        $previousPrefix = $this->prefix;
        $this->prefix = $previousPrefix . $prefix;

        $previousMiddlewares = $this->middlewares;
        $this->middlewares = array_merge($previousMiddlewares, $middlewares);

        $callback($this);

        $this->prefix = $previousPrefix;
        $this->middlewares = $previousMiddlewares;
    }

    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        $this->routes[] = [
            'method'      => $method,
            'path'        => $path,
            'handler'     => $handler,
            'middlewares' => $this->middlewares,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        // Remove query string
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            $pattern = $this->buildPattern($route['path']);

            if (preg_match($pattern, $uri, $matches)) {
                // Extract named params
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Run middlewares
                foreach ($route['middlewares'] as $middleware) {
                    $mw = new $middleware();
                    $mw->handle();
                }

                // Run handler
                $this->runHandler($route['handler'], $params);
                return;
            }
        }

        // 404
        http_response_code(404);
        if (file_exists(__DIR__ . '/../views/errors/404.php')) {
            require __DIR__ . '/../views/errors/404.php';
        } else {
            echo '<h1>404 - Halaman tidak ditemukan</h1>';
        }
    }

    private function buildPattern(string $path): string
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function runHandler(callable|array $handler, array $params = []): void
    {
        if (is_callable($handler)) {
            call_user_func($handler, $params);
            return;
        }

        if (is_array($handler) && count($handler) === 2) {
            [$controllerClass, $method] = $handler;
            $controller = new $controllerClass();
            $controller->$method($params);
            return;
        }

        throw new \RuntimeException('Invalid route handler');
    }
}