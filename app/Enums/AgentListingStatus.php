<?php

namespace App\Enums;

enum AgentListingStatus: string
{
    case Discovered = 'discovered';
    case Active = 'active';
    case Stale = 'stale';
    case Degraded = 'degraded';
    case Disabled = 'disabled';
    case Error = 'error';
}
