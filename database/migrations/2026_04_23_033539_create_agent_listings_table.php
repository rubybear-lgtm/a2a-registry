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
        Schema::create('agent_listings', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->text('source_url')->unique();
            $table->string('name');
            $table->text('description');
            $table->string('agent_version');
            $table->text('documentation_url')->nullable();
            $table->text('icon_url')->nullable();
            $table->string('provider_name')->nullable()->index();
            $table->text('preferred_interface_url');
            $table->string('preferred_protocol_binding');
            $table->string('preferred_protocol_version');
            $table->text('search_document');
            $table->boolean('has_auth')->default(false);
            $table->boolean('supports_streaming')->default(false);
            $table->boolean('supports_push_notifications')->default(false);
            $table->boolean('supports_extended_agent_card')->default(false);
            $table->string('status')->index()->default('discovered');
            $table->json('raw_card_json');
            $table->json('provider_json')->nullable();
            $table->json('capabilities_json');
            $table->json('supported_interfaces_json');
            $table->json('security_schemes_json')->nullable();
            $table->json('security_requirements_json')->nullable();
            $table->json('default_input_modes_json');
            $table->json('default_output_modes_json');
            $table->json('skills_json');
            $table->json('signatures_json')->nullable();
            $table->json('validation_warnings_json')->nullable();
            $table->string('etag')->nullable();
            $table->string('last_modified')->nullable();
            $table->string('content_type')->nullable();
            $table->string('content_hash')->nullable()->index();
            $table->unsignedSmallInteger('last_http_status')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('refresh_due_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_listings');
    }
};
