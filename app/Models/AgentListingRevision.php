<?php

namespace App\Models;

use Database\Factories\AgentListingRevisionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentListingRevision extends Model
{
    /** @use HasFactory<AgentListingRevisionFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'agent_listing_id',
        'revision_number',
        'source_url',
        'response_status',
        'content_type',
        'etag',
        'last_modified',
        'raw_body',
        'raw_card_json',
        'normalized_card_json',
        'validation_errors_json',
        'validation_warnings_json',
        'content_hash',
        'is_valid',
        'fetched_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'agent_listing_id' => 'integer',
            'revision_number' => 'integer',
            'response_status' => 'integer',
            'is_valid' => 'boolean',
            'fetched_at' => 'datetime',
            'raw_card_json' => 'array',
            'normalized_card_json' => 'array',
            'validation_errors_json' => 'array',
            'validation_warnings_json' => 'array',
        ];
    }

    public function agentListing(): BelongsTo
    {
        return $this->belongsTo(AgentListing::class);
    }
}
