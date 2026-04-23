<?php

namespace App\Actions\AgentRegistry;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use JsonException;

class FetchAgentCard
{
    /**
     * @return array{
     *     source_url: string,
     *     response_status: int,
     *     content_type: string|null,
     *     etag: string|null,
     *     last_modified: string|null,
     *     raw_body: string|null,
     *     raw_card: array<string, mixed>|null,
     *     content_hash: string|null,
     *     fetched_at: Carbon,
     *     not_modified: bool,
     * }
     */
    public function __invoke(
        string $sourceUrl,
        ?string $etag = null,
        ?string $lastModified = null,
    ): array {
        $headers = array_filter([
            'If-None-Match' => $etag,
            'If-Modified-Since' => $lastModified,
        ]);

        try {
            $response = Http::acceptJson()
                ->timeout(10)
                ->connectTimeout(5)
                ->withHeaders($headers)
                ->get($sourceUrl);
        } catch (ConnectionException) {
            throw ValidationException::withMessages([
                'source_url' => 'Unable to connect to the remote Agent Card endpoint.',
            ]);
        }

        $fetchedAt = now();

        if ($response->status() === 304) {
            return [
                'source_url' => $sourceUrl,
                'response_status' => 304,
                'content_type' => $response->header('Content-Type'),
                'etag' => $response->header('ETag'),
                'last_modified' => $response->header('Last-Modified'),
                'raw_body' => null,
                'raw_card' => null,
                'content_hash' => null,
                'fetched_at' => $fetchedAt,
                'not_modified' => true,
            ];
        }

        if (! $response->successful()) {
            throw ValidationException::withMessages([
                'source_url' => "Unable to fetch Agent Card. Received HTTP {$response->status()}.",
            ]);
        }

        $rawBody = $response->body();

        try {
            $payload = json_decode($rawBody, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw ValidationException::withMessages([
                'source_url' => 'The fetched Agent Card must be valid JSON.',
            ]);
        }

        if (! is_array($payload) || array_is_list($payload)) {
            throw ValidationException::withMessages([
                'source_url' => 'The fetched Agent Card must be a JSON object.',
            ]);
        }

        return [
            'source_url' => $sourceUrl,
            'response_status' => $response->status(),
            'content_type' => $response->header('Content-Type'),
            'etag' => $response->header('ETag'),
            'last_modified' => $response->header('Last-Modified'),
            'raw_body' => $rawBody,
            'raw_card' => $payload,
            'content_hash' => hash('sha256', $rawBody),
            'fetched_at' => $fetchedAt,
            'not_modified' => false,
        ];
    }
}
