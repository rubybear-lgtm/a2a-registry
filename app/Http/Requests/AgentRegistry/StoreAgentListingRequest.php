<?php

namespace App\Http\Requests\AgentRegistry;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAgentListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $configuredToken = (string) config('agent-registry.management_token', '');
        $providedToken = $this->bearerToken() ?? (string) $this->header('X-Agent-Registry-Token', '');

        if ($configuredToken === '' || $providedToken === '') {
            return false;
        }

        return hash_equals($configuredToken, $providedToken);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'source_url' => [
                'required',
                'url:https',
                Rule::unique('agent_listings', 'source_url'),
            ],
        ];
    }
}
