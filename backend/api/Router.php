<?php

class Router
{
    private array $routes = [];
    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function put($uri, $action)
    {
        $this->addRoute('PUT', $uri, $action);
    }

    public function delete($uri, $action)
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    private function addRoute($method, $uri, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action
        ];
    }

    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_GET['route'] ?? '';
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['uri'] === $uri) {
                [$controller, $function] = $route['action'];
                require_once __DIR__ . "/../controllers/" . basename(str_replace("\\", "/", $controller)) . ".php";
                $obj = new $controller();
                $obj->$function();
                return;
            }
        }

        http_response_code(404);
        echo json_encode([
            "status" => false,
            "message" => "Route tidak ditemukan"
        ]);
    }
}