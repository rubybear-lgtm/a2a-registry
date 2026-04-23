<?php

return [
    'operator_emails' => array_values(array_filter(array_map(
        static fn (string $email): string => trim($email),
        explode(',', (string) env('AGENT_REGISTRY_OPERATOR_EMAILS', '')),
    ))),

    'refresh_interval_minutes' => (int) env('AGENT_REGISTRY_REFRESH_INTERVAL_MINUTES', 1_440),
];
