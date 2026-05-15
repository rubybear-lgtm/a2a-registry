CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "two_factor_secret" text,
  "two_factor_recovery_codes" text,
  "two_factor_confirmed_at" datetime
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE INDEX "cache_expiration_index" on "cache"("expiration");
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE INDEX "cache_locks_expiration_index" on "cache_locks"("expiration");
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "agent_listings"(
  "id" integer primary key autoincrement not null,
  "public_id" varchar not null,
  "source_url" text not null,
  "name" varchar not null,
  "description" text not null,
  "agent_version" varchar not null,
  "documentation_url" text,
  "icon_url" text,
  "provider_name" varchar,
  "preferred_interface_url" text not null,
  "preferred_protocol_binding" varchar not null,
  "preferred_protocol_version" varchar not null,
  "search_document" text not null,
  "has_auth" tinyint(1) not null default '0',
  "supports_streaming" tinyint(1) not null default '0',
  "supports_push_notifications" tinyint(1) not null default '0',
  "supports_extended_agent_card" tinyint(1) not null default '0',
  "status" varchar not null default 'discovered',
  "raw_card_json" text not null,
  "provider_json" text,
  "capabilities_json" text not null,
  "supported_interfaces_json" text not null,
  "security_schemes_json" text,
  "security_requirements_json" text,
  "default_input_modes_json" text not null,
  "default_output_modes_json" text not null,
  "skills_json" text not null,
  "signatures_json" text,
  "validation_warnings_json" text,
  "etag" varchar,
  "last_modified" varchar,
  "content_type" varchar,
  "content_hash" varchar,
  "last_http_status" integer,
  "last_error" text,
  "fetched_at" datetime,
  "validated_at" datetime,
  "refresh_due_at" datetime,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "agent_listings_public_id_unique" on "agent_listings"(
  "public_id"
);
CREATE UNIQUE INDEX "agent_listings_source_url_unique" on "agent_listings"(
  "source_url"
);
CREATE INDEX "agent_listings_provider_name_index" on "agent_listings"(
  "provider_name"
);
CREATE INDEX "agent_listings_status_index" on "agent_listings"("status");
CREATE INDEX "agent_listings_content_hash_index" on "agent_listings"(
  "content_hash"
);
CREATE TABLE IF NOT EXISTS "agent_listing_revisions"(
  "id" integer primary key autoincrement not null,
  "agent_listing_id" integer not null,
  "revision_number" integer not null,
  "source_url" text not null,
  "response_status" integer,
  "content_type" varchar,
  "etag" varchar,
  "last_modified" varchar,
  "raw_body" text,
  "raw_card_json" text,
  "normalized_card_json" text,
  "validation_errors_json" text,
  "validation_warnings_json" text,
  "content_hash" varchar,
  "is_valid" tinyint(1) not null default '0',
  "fetched_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("agent_listing_id") references "agent_listings"("id") on delete cascade
);
CREATE UNIQUE INDEX "agent_listing_revisions_agent_listing_id_revision_number_unique" on "agent_listing_revisions"(
  "agent_listing_id",
  "revision_number"
);
CREATE INDEX "agent_listing_revisions_content_hash_index" on "agent_listing_revisions"(
  "content_hash"
);

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_08_14_170933_add_two_factor_columns_to_users_table',1);
INSERT INTO migrations VALUES(5,'2026_04_23_033539_create_agent_listings_table',2);
INSERT INTO migrations VALUES(6,'2026_04_23_033542_create_agent_listing_revisions_table',2);
