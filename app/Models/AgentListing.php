<?php

namespace App\Models;

use App\Enums\AgentListingStatus;
use Database\Factories\AgentListingFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AgentListing extends Model
{
    /** @use HasFactory<AgentListingFactory> */
    use HasFactory, HasUlids;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'source_url',
        'name',
        'description',
        'agent_version',
        'documentation_url',
        'icon_url',
        'provider_name',
        'preferred_interface_url',
        'preferred_protocol_binding',
        'preferred_protocol_version',
        'search_document',
        'has_auth',
        'supports_streaming',
        'supports_push_notifications',
        'supports_extended_agent_card',
        'status',
        'raw_card_json',
        'provider_json',
        'capabilities_json',
        'supported_interfaces_json',
        'security_schemes_json',
        'security_requirements_json',
        'default_input_modes_json',
        'default_output_modes_json',
        'skills_json',
        'signatures_json',
        'validation_warnings_json',
        'etag',
        'last_modified',
        'content_type',
        'content_hash',
        'last_http_status',
        'last_error',
        'fetched_at',
        'validated_at',
        'refresh_due_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => AgentListingStatus::class,
            'has_auth' => 'boolean',
            'supports_streaming' => 'boolean',
            'supports_push_notifications' => 'boolean',
            'supports_extended_agent_card' => 'boolean',
            'provider_json' => 'array',
            'capabilities_json' => 'array',
            'supported_interfaces_json' => 'array',
            'security_schemes_json' => 'array',
            'security_requirements_json' => 'array',
            'default_input_modes_json' => 'array',
            'default_output_modes_json' => 'array',
            'skills_json' => 'array',
            'signatures_json' => 'array',
            'validation_warnings_json' => 'array',
            'raw_card_json' => 'array',
            'last_http_status' => 'integer',
            'fetched_at' => 'datetime',
            'validated_at' => 'datetime',
            'refresh_due_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    /**
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['public_id'];
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(AgentListingRevision::class);
    }

    public function latestRevision(): HasOne
    {
        return $this->hasOne(AgentListingRevision::class)->latestOfMany('revision_number');
    }
}
