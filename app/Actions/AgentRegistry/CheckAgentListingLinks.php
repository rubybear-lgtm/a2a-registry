<?php

namespace App\Actions\AgentRegistry;

use App\Models\AgentListing;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CheckAgentListingLinks
{
    /**
     * @return list<string>
     */
    public function __invoke(AgentListing $agentListing): array
    {
        $messages = [];

        foreach ($this->collectUrlTargets($agentListing->raw_card_json ?? []) as $target) {
            try {
                $response = Http::timeout(10)
                    ->connectTimeout(5)
                    ->head($target['url']);

                if ($response->status() === 405) {
                    $response = Http::timeout(10)
                        ->connectTimeout(5)
                        ->get($target['url']);
                }
            } catch (ConnectionException) {
                $messages[] = sprintf(
                    '%s (%s) could not be reached.',
                    $target['label'],
                    $target['url'],
                );

                continue;
            }

            if ($this->isReachableStatus($response->status())) {
                continue;
            }

            $messages[] = sprintf(
                '%s (%s) returned HTTP %d.',
                $target['label'],
                $target['url'],
                $response->status(),
            );
        }

        return array_values(array_unique($messages));
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return list<array{label: string, url: string}>
     */
    private function collectUrlTargets(array $payload, string $path = ''): array
    {
        $targets = [];

        foreach ($payload as $key => $value) {
            $segment = is_int($key) ? (string) $key : $key;
            $currentPath = $path === '' ? $segment : "{$path}.{$segment}";

            if (is_string($value) && $this->isHttpUrl($value) && $this->looksLikeUrlField($segment)) {
                $targets[] = [
                    'label' => $this->formatLabel($currentPath),
                    'url' => $value,
                ];

                continue;
            }

            if (is_array($value)) {
                $targets = [...$targets, ...$this->collectUrlTargets($value, $currentPath)];
            }
        }

        return $targets;
    }

    private function looksLikeUrlField(string $key): bool
    {
        return Str::endsWith($key, ['url', 'Url']);
    }

    private function isHttpUrl(string $value): bool
    {
        return Str::startsWith($value, ['http://', 'https://']);
    }

    private function formatLabel(string $path): string
    {
        return str_replace('.url', '.URL', $path);
    }

    private function isReachableStatus(int $status): bool
    {
        return ($status >= 200 && $status < 400) || in_array($status, [401, 403, 429], true);
    }
}
