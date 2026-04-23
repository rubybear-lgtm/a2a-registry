<?php

use App\Http\Controllers\AgentRegistry\AgentListingManagementController;
use App\Http\Controllers\AgentRegistry\AgentListingWebController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::inertia('dashboard', 'dashboard')->name('dashboard');

Route::get('agents', [AgentListingWebController::class, 'index'])->name('agents.index');
Route::get('agents/{agentListing}', [AgentListingWebController::class, 'show'])->name('agents.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('manage/agent-listings', [AgentListingManagementController::class, 'store'])
        ->middleware('throttle:30,1')
        ->name('agent-listings.store');

    Route::post('manage/agent-listings/{agentListing}/refresh', [AgentListingManagementController::class, 'refresh'])
        ->middleware('throttle:30,1')
        ->name('agent-listings.refresh');
});

require __DIR__.'/settings.php';
