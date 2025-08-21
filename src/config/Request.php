<?php

namespace PoolNET\config;

use PoolNET\interface\config\Request as RequestInterface;

class Request implements RequestInterface
{
    private string $uri;
    public ?array $headers;
    private ?array $cookieParams;
    private ?string $method;
    private ?array $body;
    public function __construct()
    {
        $this->uri = $this->getUri();
        $this->headers = $this->getHeaders();
        $this->cookieParams = $_COOKIE;
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->body = $this->parseBody();
    }

    public function getUri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
    public function getPath(): string
    {
        $route = explode("/PoolNET", $this->uri)[1];
        return explode('?', $route)[0];
    }
    public function withPath(string $path): self
    {
        $this->uri = "/PoolNET" . $path;
        return $this;
    }
    public function getParams(): string
    {
        return explode('?', $this->uri)[1] ?? "";
    }
    public function getMethod(): string
    {
        return $this->method;
    }
    public function withMethod(string $method): self
    {
        $this->method = strtolower($method);
        return $this;
    }
    public function getHeaders(): array
    {
        // return apache_request_headers();
        $headers = array();
        foreach ($_SERVER as $k => $v) {
            if (substr($k, 0, 5) == "HTTP_") {
                $k = str_replace('_', ' ', substr($k, 5));
                $k = str_replace(' ', '-', ucwords(strtolower($k)));
                $headers[$k] = $v;
            } else {
                $k = str_replace('_', ' ', $k);
                $k = str_replace(' ', '-', ucwords(strtolower($k)));
                $headers[$k] = $v;
            }
        }
        return $headers;
    }
    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }
    public function withCookieParams(array $cookieParams): self
    {
        foreach ($cookieParams as $key => $value) {
            $this->cookieParams[$key] = $value;
        }
        return $this;
    }
    public function getParsedBody(): array
    {
        return $this->body ? $this->body : [];
    }
    private function parseBody(): ?array
    {
        if (isset($this->headers["Content-Type"]) && $this->headers["Content-Type"] === "application/x-www-form-urlencoded") {
            return $_POST;
        }
        return json_decode(file_get_contents('php://input'), true);
    }
    public function withBody(array $body): self
    {
        $this->body = $body;
        return $this;
    }
}
