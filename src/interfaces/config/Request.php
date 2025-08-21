<?php

namespace PoolNET\interface\config;

interface Request
{
    public function getHeaders(): array;
    public function getMethod(): string;
    public function getCookieParams(): array;
    public function getUri(): string;
    public function getPath(): string;
    public function getParams(): string;
    public function getParsedBody(): array;
    public function withBody(array $body): self;
}
