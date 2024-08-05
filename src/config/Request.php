<?php

namespace PoolNET\config;

class Request
{
    private string $uri;
    public string $routerPath;
    public ?array $body;
    public function __construct()
    {
        $this->uri = $this->getUri();
        $this->routerPath = $this->getPath();
        $this->body = json_decode(file_get_contents('php://input'), true);
    }

    private function getUri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
    public function getPath(): string
    {
        $route = explode("/PoolNET", $this->uri)[1];
        return explode('?', $route)[0];
    }
    public function getParams(): string
    {
        return explode('?', $this->uri)[1] ?? "";
    }
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    public function getHeaders(): false | array
    {
        return getallheaders();
    }
    public function getParsedBody(): array
    {
        return $this->body;
    }
}
