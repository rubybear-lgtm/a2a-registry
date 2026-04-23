<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agent_listing_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_listing_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('revision_number');
            $table->text('source_url');
            $table->unsignedSmallInteger('response_status')->nullable();
            $table->string('content_type')->nullable();
            $table->string('etag')->nullable();
            $table->string('last_modified')->nullable();
            $table->longText('raw_body')->nullable();
            $table->json('raw_card_json')->nullable();
            $table->json('normalized_card_json')->nullable();
            $table->json('validation_errors_json')->nullable();
            $table->json('validation_warnings_json')->nullable();
            $table->string('content_hash')->nullable()->index();
            $table->boolean('is_valid')->default(false);
            $table->timestamp('fetched_at')->nullable();
            $table->unique(['agent_listing_id', 'revision_number']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_listing_revisions');
    }
};
