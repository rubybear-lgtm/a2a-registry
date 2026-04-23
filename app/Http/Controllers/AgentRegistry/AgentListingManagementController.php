<?php

namespace App\Http\Controllers\AgentRegistry;

use App\Actions\AgentRegistry\IngestAgentCard;
use App\Http\Controllers\Controller;
use App\Http\Requests\AgentRegistry\StoreAgentListingRequest;
use App\Models\AgentListing;
use Illuminate\Auth\Access\AuthorizationException;
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
        $this->authorizeOperator((string) $request->user()?->email);

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

    /**
     * Refresh an existing Agent Card listing from its canonical source URL.
     *
     * @throws AuthorizationException
     */
    public function refresh(
        AgentListing $agentListing,
        IngestAgentCard $ingestAgentCard,
    ): JsonResponse {
        $this->authorizeOperator((string) request()->user()?->email);

        $refreshedListing = $ingestAgentCard($agentListing->source_url);

        return response()->json([
            'data' => [
                'id' => $refreshedListing->public_id,
                'status' => $refreshedListing->status->value,
                'source_url' => $refreshedListing->source_url,
                'name' => $refreshedListing->name,
                'preferred_interface_url' => $refreshedListing->preferred_interface_url,
            ],
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    private function authorizeOperator(string $email): void
    {
        $allowedEmails = array_map(
            static fn (string $value): string => mb_strtolower($value),
            config('agent-registry.operator_emails', []),
        );

        throw_if(
            ! in_array(mb_strtolower($email), $allowedEmails, true),
            AuthorizationException::class,
        );
    }
}
