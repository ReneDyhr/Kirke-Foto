<?php

declare(strict_types=1);

namespace App\Sentry;

use Illuminate\Support\Facades\App;
use Sentry\Event;
use Sentry\EventHint;

/**
 * Sentry's RequestIntegration uses $_SERVER REMOTE_ADDR (proxy IP behind load balancers).
 * Laravel's Request::ip() respects trusted proxies (X-Forwarded-For). Apply that IP to events.
 */
final class FixClientIpBeforeSend
{
    public static function handle(Event $event, ?EventHint $hint): Event
    {
        if (App::runningInConsole()) {
            return $event;
        }

        $ip = \request()->ip();

        if ($ip === null || $ip === '') {
            return $event;
        }

        $requestContext = $event->getRequest();
        $env = $requestContext['env'] ?? [];

        if (!\is_array($env)) {
            $env = [];
        }

        /** @var array<string, mixed> $env */
        $env['REMOTE_ADDR'] = $ip;
        $requestContext['env'] = $env;
        $event->setRequest($requestContext);

        $user = $event->getUser();

        if ($user !== null) {
            $user->setIpAddress($ip);
            $event->setUser($user);
        }

        return $event;
    }
}
