<?php

use App\Http\Controllers\AgentRegistry\AgentListingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('agents', [AgentListingController::class, 'index'])
        ->name('api.v1.agent-listings.index');

    Route::get('agents/{agentListing}', [AgentListingController::class, 'show'])
        ->name('api.v1.agent-listings.show');

    Route::get('agents/{agentListing}/card', [AgentListingController::class, 'card'])
        ->name('api.v1.agent-listings.card');
});
