<?php

namespace PoolNET\interface\config;

use Throwable;

interface Response
{
    public function withHeader(string $name, string|array $value): self;
    public function withStatus(int $code): self;
    public function toJson(?array $data): void;
    public function handleError(Throwable $th): void;
}
