<?php

declare(strict_types=1);

namespace App;

class Router
{
    private array $handlers = [];
    private $notFoundHandler;
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';

    public function get(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_GET, $path, $handler);

    }

    public function post(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_POST, $path, $handler);
    }

    public function addNotFoundHandler($handler): void
    {
        $this->notFoundHandler = $handler;
    }

    private function addHandler(string $method, string $path, $handler): void
    {
        $this->handlers[$method . $path] = [
            'path' => $path,
            'method' => $method,
            'handler' => $handler,
        ];
    }

    public function run()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];

        $callback = null;
        foreach ($this->handlers as $handler) {
            if ($handler['path'] === $requestPath && $handler['method'] === $_SERVER['REQUEST_METHOD']) {
                $callback = $handler['handler'];
            }
        }
        
        if (!$callback) {
            header('HTTP/1.0 404 Not Found');
            if ($this->notFoundHandler) {
                $callback = $this->notFoundHandler;
            }
        }
        
        if (is_array($callback) && count($callback) === 2) {
            $callback = [new $callback[0], $callback[1]];
        } elseif (is_array($callback) && count($callback) === 1) {
            $callback = [new $callback[0], '__invoke'];
        }

        call_user_func_array($callback, array_merge($_GET, $_POST));
    }
}