<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middleware = [];

    public function get(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function put(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    public function delete(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    private function addRoute(string $method, string $path, $handler, array $middleware): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $path)) {
                $params = $this->extractParams($route['path'], $path);
                
                // Run middleware
                foreach ($route['middleware'] as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    if (!$middleware->handle()) {
                        return;
                    }
                }

                // Execute handler
                if (is_array($route['handler'])) {
                    [$controllerClass, $method] = $route['handler'];
                    $controller = new $controllerClass();
                    $controller->$method($params);
                } else {
                    $route['handler']($params);
                }
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        include __DIR__ . '/../../views/errors/404.php';
    }

    private function matchPath(string $routePath, string $requestPath): bool
    {
        $routePattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $routePattern = '#^' . $routePattern . '$#';
        return preg_match($routePattern, $requestPath);
    }

    private function extractParams(string $routePath, string $requestPath): array
    {
        $routeParts = explode('/', trim($routePath, '/'));
        $requestParts = explode('/', trim($requestPath, '/'));
        
        $params = [];
        foreach ($routeParts as $index => $part) {
            if (preg_match('/\{([^}]+)\}/', $part, $matches)) {
                $params[$matches[1]] = $requestParts[$index] ?? null;
            }
        }
        
        return $params;
    }
}