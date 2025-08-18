<?php

namespace PoolNET\config;

use PDOException;
use PoolNET\interface\config\Response as ResponseInterface;
use Throwable;

class Response implements ResponseInterface
{
    private array $headers = [];
    private int $status = 200;

    public function withHeader(string $name, string|array $value): self
    {
        $this->headers[$name] = is_string($value) ? $value : implode(',', $value);
        return $this;
    }
    private function sendHeaders(): void
    {
        foreach ($this->headers as $name => $value) {
            header(sprintf('%s: %s', $name, $value));
        }
    }

    public function withStatus(int $code): self
    {
        $this->status = $code;
        return $this;
    }

    public function toJson(?array $data): void
    {
        $this->withHeader('Content-Type', 'application/json');
        $this->sendHeaders();
        $data ??= $this->defaultResponse();
        http_response_code($this->status);
        echo json_encode($data);
    }
    private function defaultResponse(): array
    {
        $response = array();
        switch ($this->status) {
            case 405:
                $response = ["error" => "Mètode no permès"];
                break;
            case 500:
            default:
                $response = ["error" => "Alguna cosa ha fallat"];
                break;
        }
        return $response;
    }
    public function handleError(Throwable $th): void
    {
        switch ($th) {
            case $th instanceof PDOException:
                // TODO: Log error per a us intern
                $this->withStatus(500)->toJson(['error' => "Error amb la base de dades"]);
                break;
            default:
                $this->withStatus(500)->toJson(null);
                break;
        }
    }
}
