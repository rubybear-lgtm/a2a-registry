<?php

use App\Http\Controllers\AgentRegistry\AgentListingManagementController;
use App\Http\Controllers\AgentRegistry\AgentListingWebController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('home');

Route::get('agents', [AgentListingWebController::class, 'index'])->name('agents.index');
Route::get('agents/{agentListing}', [AgentListingWebController::class, 'show'])->name('agents.show');

Route::post('manage/agent-listings', [AgentListingManagementController::class, 'store'])
    ->middleware('throttle:30,1')
    ->name('agent-listings.store');
