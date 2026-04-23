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
        $user = $this->user();

        if (! $user) {
            return false;
        }

        $operatorEmails = array_map(
            static fn (string $email): string => mb_strtolower($email),
            config('agent-registry.operator_emails', []),
        );

        return in_array(mb_strtolower((string) $user->email), $operatorEmails, true);
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
