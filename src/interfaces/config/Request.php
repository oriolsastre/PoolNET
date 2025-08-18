<?php

namespace PoolNET\interface\config;

interface Request
{
    public function getUri(): string;
    public function getPath(): string;
    public function getParams(): string;
    public function getMethod(): string;
    public function getHeaders(): array;
    public function getParsedBody(): array;
}
