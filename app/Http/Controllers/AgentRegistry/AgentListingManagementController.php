<?php

namespace App\Http\Controllers\AgentRegistry;

use App\Actions\AgentRegistry\IngestAgentCard;
use App\Http\Controllers\Controller;
use App\Http\Requests\AgentRegistry\StoreAgentListingRequest;
use Illuminate\Http\JsonResponse;

class AgentListingManagementController extends Controller
{
    /**
     * Ingest and validate a public Agent Card into the registry.
     */
    public function store(
        StoreAgentListingRequest $request,
        IngestAgentCard $ingestAgentCard,
    ): JsonResponse {
        $agentListing = $ingestAgentCard(
            $request->string('source_url')->toString(),
        );

        return response()->json([
            'data' => [
                'id' => $agentListing->public_id,
                'status' => $agentListing->status->value,
                'source_url' => $agentListing->source_url,
                'name' => $agentListing->name,
                'preferred_interface_url' => $agentListing->preferred_interface_url,
            ],
        ], 201);
    }
}
