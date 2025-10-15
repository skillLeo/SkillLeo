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
    protected int $connectTimeout;

    public function __construct()
    {
        $this->baseUrl        = rtrim(config('services.cv_ai.base_url', env('CV_AI_BASE_URL', 'http://127.0.0.1:8000')), '/');
        $this->uploadPath     = '/' . ltrim(config('services.cv_ai.upload_path', env('CV_AI_UPLOAD_PATH', '/upload_resume/')), '/');
        $this->timeout        = (int) (config('services.cv_ai.timeout', env('CV_AI_TIMEOUT', 300)));
        $this->connectTimeout = 8; // quick fail if FastAPI isn’t reachable
    }

    public function parseResume(UploadedFile|string $fileOrPath): array
    {
        if ($fileOrPath instanceof UploadedFile) {
            $path     = $fileOrPath->getRealPath();
            $filename = $fileOrPath->getClientOriginalName();
        } else {
            $path     = $fileOrPath;
            $filename = basename($fileOrPath);
        }

        if (!$path || !is_readable($path)) {
            throw new RuntimeException('Resume file not readable at: '.$path);
        }

        try {
            $res = Http::timeout($this->timeout)            // total time budget
                ->connectTimeout($this->connectTimeout)     // TCP connect time
                ->retry(1, 1500, throw: false)              // one fast retry
                ->acceptJson()
                ->attach('file', file_get_contents($path), $filename)
                ->post($this->baseUrl.$this->uploadPath);
        } catch (ConnectionException $e) {
            throw new RuntimeException(
                'Could not reach CV AI service at '.$this->baseUrl.$this->uploadPath.'. '.$e->getMessage(),
                previous: $e
            );
        }

        if ($res->failed()) {
            $body = (string) $res->body();
            if (strlen($body) > 600) $body = substr($body, 0, 600).'…';
            throw new RuntimeException('CV AI service error (HTTP '.$res->status().'): '.$body);
        }

        $json    = $res->json();
        $payload = $json['json_output'] ?? null;

        if (is_array($payload)) return $payload;

        if (is_string($payload)) {
            $decoded = json_decode($payload, true);
            if (json_last_error() === JSON_ERROR_NONE) return $decoded;
        }

        throw new RuntimeException('Invalid response from CV AI service — "json_output" missing or malformed.');
    }
}
