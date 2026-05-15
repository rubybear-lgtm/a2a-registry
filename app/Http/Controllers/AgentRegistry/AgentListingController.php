<?php

namespace App\Http\Controllers\AgentRegistry;

use App\Enums\AgentListingStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\AgentListingCardResource;
use App\Http\Resources\AgentListingResource;
use App\Models\AgentListing;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class AgentListingController extends Controller
{
    private const PER_PAGE = 15;

    /**
     * @var array<int, AgentListingStatus>
     */
    private const PUBLIC_STATUSES = [
        AgentListingStatus::Active,
        AgentListingStatus::Stale,
        AgentListingStatus::Degraded,
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        request()->validate([
            'q' => ['sometimes', 'string'],
            'status' => ['sometimes', Rule::in([
                AgentListingStatus::Active->value,
                AgentListingStatus::Stale->value,
                AgentListingStatus::Degraded->value,
            ])],
        ]);

        $query = AgentListing::query()
            ->whereIn('status', self::PUBLIC_STATUSES);

        if ($status = request()->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($search = trim(request()->string('q')->toString())) {
            $query->where(function ($builder) use ($search): void {
                $builder
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('provider_name', 'like', "%{$search}%")
                    ->orWhere('search_document', 'like', "%{$search}%");
            });
        }

        return AgentListingResource::collection(
            $query->orderBy('name')->paginate(self::PER_PAGE)->withQueryString(),
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(AgentListing $agentListing): AgentListingResource
    {
        abort_unless($this->isPubliclyVisible($agentListing), 404);

        return new AgentListingResource($agentListing);
    }

    /**
     * Return the raw Agent Card for the specified registry entry.
     */
    public function card(AgentListing $agentListing): AgentListingCardResource
    {
        abort_unless($this->isPubliclyVisible($agentListing), 404);

        return new AgentListingCardResource($agentListing);
    }

    private function isPubliclyVisible(AgentListing $agentListing): bool
    {
        return in_array($agentListing->status, self::PUBLIC_STATUSES, true);
    }
}
