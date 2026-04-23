<?php

namespace App\Actions\AgentRegistry;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator as ValidatorContract;

class ValidateAgentCard
{
    /**
     * @param  array<string, mixed>  $agentCard
     * @return array{card: array<string, mixed>, warnings: array<string, list<string>>}
     *
     * @throws ValidationException
     */
    public function __invoke(array $agentCard): array
    {
        $warnings = [];
        $canonicalCard = $agentCard;

        if (array_key_exists('security', $canonicalCard) && ! array_key_exists('securityRequirements', $canonicalCard)) {
            $canonicalCard['securityRequirements'] = $canonicalCard['security'];
            $warnings['securityRequirements'] = [
                'The legacy security field was mapped to securityRequirements during ingestion.',
            ];
        }

        $validator = Validator::make($canonicalCard, [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'supportedInterfaces' => ['required', 'array', 'min:1'],
            'supportedInterfaces.*' => ['required', 'array:url,protocolBinding,protocolVersion,tenant'],
            'supportedInterfaces.*.url' => ['required', 'string', 'url'],
            'supportedInterfaces.*.protocolBinding' => ['required', 'string'],
            'supportedInterfaces.*.protocolVersion' => ['required', 'string', 'regex:/^\d+\.\d+$/'],
            'supportedInterfaces.*.tenant' => ['sometimes', 'nullable', 'string'],
            'provider' => ['sometimes', 'array:organization,url'],
            'provider.organization' => ['required_with:provider', 'string'],
            'provider.url' => ['required_with:provider', 'string', 'url:https'],
            'version' => ['required', 'string'],
            'documentationUrl' => ['sometimes', 'nullable', 'string', 'url:https'],
            'iconUrl' => ['sometimes', 'nullable', 'string', 'url:https'],
            'capabilities' => ['present', 'array'],
            'capabilities.streaming' => ['sometimes', 'boolean'],
            'capabilities.pushNotifications' => ['sometimes', 'boolean'],
            'capabilities.extendedAgentCard' => ['sometimes', 'boolean'],
            'defaultInputModes' => ['required', 'array', 'min:1'],
            'defaultInputModes.*' => ['required', 'string'],
            'defaultOutputModes' => ['required', 'array', 'min:1'],
            'defaultOutputModes.*' => ['required', 'string'],
            'skills' => ['present', 'array'],
            'skills.*' => ['required', 'array:id,name,description,tags,examples,inputModes,outputModes,securityRequirements'],
            'skills.*.id' => ['required', 'string'],
            'skills.*.name' => ['required', 'string'],
            'skills.*.description' => ['required', 'string'],
            'skills.*.tags' => ['required', 'array'],
            'skills.*.tags.*' => ['required', 'string'],
            'skills.*.examples' => ['sometimes', 'array'],
            'skills.*.examples.*' => ['required', 'string'],
            'skills.*.inputModes' => ['sometimes', 'array'],
            'skills.*.inputModes.*' => ['required', 'string'],
            'skills.*.outputModes' => ['sometimes', 'array'],
            'skills.*.outputModes.*' => ['required', 'string'],
            'skills.*.securityRequirements' => ['sometimes', 'array'],
            'securitySchemes' => ['sometimes', 'array'],
            'securityRequirements' => ['sometimes', 'array'],
            'signatures' => ['sometimes', 'array'],
        ]);

        $validator->after(function (ValidatorContract $validator) use ($canonicalCard): void {
            $this->validateSupportedInterfaceUrls($canonicalCard, $validator);
            $this->validateSecuritySchemes(
                Arr::get($canonicalCard, 'securitySchemes'),
                'securitySchemes',
                $validator,
            );
            $this->validateSecurityRequirements(
                Arr::get($canonicalCard, 'securityRequirements'),
                'securityRequirements',
                $validator,
            );

            foreach (Arr::get($canonicalCard, 'skills', []) as $index => $skill) {
                $this->validateSecurityRequirements(
                    Arr::get($skill, 'securityRequirements'),
                    "skills.{$index}.securityRequirements",
                    $validator,
                );
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return [
            'card' => $canonicalCard,
            'warnings' => $warnings,
        ];
    }

    /**
     * @param  array<string, mixed>  $agentCard
     */
    private function validateSupportedInterfaceUrls(array $agentCard, ValidatorContract $validator): void
    {
        foreach (Arr::get($agentCard, 'supportedInterfaces', []) as $index => $interface) {
            $url = (string) Arr::get($interface, 'url', '');
            $parts = parse_url($url);

            if ($url === '' || $parts === false) {
                continue;
            }

            $scheme = mb_strtolower((string) ($parts['scheme'] ?? ''));
            $host = mb_strtolower((string) ($parts['host'] ?? ''));

            if ($scheme !== 'https' && ! in_array($host, ['localhost', '127.0.0.1'], true)) {
                $validator->errors()->add(
                    "supportedInterfaces.{$index}.url",
                    'Supported interface URLs must use HTTPS unless they target localhost.',
                );
            }
        }
    }

    private function validateSecuritySchemes(
        mixed $securitySchemes,
        string $attribute,
        ValidatorContract $validator,
    ): void {
        if ($securitySchemes === null) {
            return;
        }

        if (! is_array($securitySchemes) || array_is_list($securitySchemes)) {
            $validator->errors()->add($attribute, 'Security schemes must be an object map.');

            return;
        }

        $allowedKeys = [
            'apiKeySecurityScheme',
            'httpAuthSecurityScheme',
            'oauth2SecurityScheme',
            'openIdConnectSecurityScheme',
            'mtlsSecurityScheme',
        ];

        foreach ($securitySchemes as $schemeName => $scheme) {
            if (! is_string($schemeName) || $schemeName === '' || ! is_array($scheme) || array_is_list($scheme)) {
                $validator->errors()->add($attribute, 'Each security scheme must be a named object.');

                continue;
            }

            $presentKeys = array_values(array_intersect(array_keys($scheme), $allowedKeys));

            if (count($presentKeys) !== 1) {
                $validator->errors()->add(
                    "{$attribute}.{$schemeName}",
                    'Each security scheme must declare exactly one supported security scheme variant.',
                );
            }
        }
    }

    private function validateSecurityRequirements(
        mixed $securityRequirements,
        string $attribute,
        ValidatorContract $validator,
    ): void {
        if ($securityRequirements === null) {
            return;
        }

        if (! is_array($securityRequirements)) {
            $validator->errors()->add($attribute, 'Security requirements must be an array.');

            return;
        }

        foreach ($securityRequirements as $index => $requirement) {
            if (! is_array($requirement) || array_is_list($requirement)) {
                $validator->errors()->add(
                    "{$attribute}.{$index}",
                    'Each security requirement must be an object map of scheme names to scope arrays.',
                );

                continue;
            }

            foreach ($requirement as $schemeName => $scopes) {
                if (! is_string($schemeName) || $schemeName === '') {
                    $validator->errors()->add(
                        "{$attribute}.{$index}",
                        'Security requirement scheme names must be non-empty strings.',
                    );
                }

                if (! is_array($scopes) || array_filter($scopes, static fn (mixed $scope): bool => ! is_string($scope)) !== []) {
                    $validator->errors()->add(
                        "{$attribute}.{$index}.{$schemeName}",
                        'Security requirement scopes must be arrays of strings.',
                    );
                }
            }
        }
    }
}
