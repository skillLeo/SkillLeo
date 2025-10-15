<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;
use RuntimeException;

class CvAiClient
{
    protected string $baseUrl;
    protected string $uploadPath;
    protected int $timeout;
    protected int $connectTimeout = 5;

    public function __construct()
    {
        $this->baseUrl   = rtrim(config('services.cv_ai.base_url', 'http://127.0.0.1:8000'), '/');
        $this->uploadPath= '/' . ltrim(config('services.cv_ai.upload_path', '/upload_resume/'), '/');
        $this->timeout   = (int) config('services.cv_ai.timeout', 180);
    }

    public function health(): array
    {
        try {
            $res = Http::connectTimeout($this->connectTimeout)
                ->timeout(5)
                ->get($this->baseUrl.'/health');

            if ($res->failed()) {
                throw new RuntimeException('Health check failed (HTTP '.$res->status().')');
            }
            return $res->json();
        } catch (ConnectionException $e) {
            throw new RuntimeException('Health check unreachable at '.$this->baseUrl.'/health. '.$e->getMessage());
        }
    }

    public function parseResume(UploadedFile|string $fileOrPath): array
    {
        if ($fileOrPath instanceof UploadedFile) {
            $path = $fileOrPath->getRealPath();
            $name = $fileOrPath->getClientOriginalName();
        } else {
            $path = $fileOrPath;
            $name = basename($fileOrPath);
        }
        if (!$path || !is_readable($path)) {
            throw new RuntimeException('Resume file not readable at: '.$path);
        }

        try {
            $res = Http::connectTimeout($this->connectTimeout)
                ->timeout($this->timeout)
                ->retry(1, 1500, throw: false)
                ->acceptJson()
                ->attach('file', file_get_contents($path), $name)
                ->post($this->baseUrl.$this->uploadPath);
        } catch (ConnectionException $e) {
            throw new RuntimeException(
                'Could not reach CV AI service at '.$this->baseUrl.$this->uploadPath.'. '.$e->getMessage()
            );
        }

        if ($res->failed()) {
            $body = (string) $res->body();
            if (strlen($body) > 600) $body = substr($body, 0, 600).'…';
            throw new RuntimeException('CV AI service error (HTTP '.$res->status().'): '.$body);
        }

        $json = $res->json();
        $payload = $json['json_output'] ?? null;

        if (is_array($payload)) return $payload;
        if (is_string($payload)) {
            $decoded = json_decode($payload, true);
            if (json_last_error() === JSON_ERROR_NONE) return $decoded;
        }
        throw new RuntimeException('Invalid response — "json_output" missing or malformed.');
    }
}
