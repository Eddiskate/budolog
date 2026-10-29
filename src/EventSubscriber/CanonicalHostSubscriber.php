<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Keep a single canonical origin: https://budolog.pl (no www, no http).
 * Fixes GSC "page contains a redirect" noise from mixed-scheme Location headers.
 */
class CanonicalHostSubscriber implements EventSubscriberInterface
{
    private const CANONICAL_HOST = 'budolog.pl';

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => ['onKernelRequest', 100]];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $host = strtolower($request->getHost());

        // Only act on the production domain (skip localhost / staging hosts).
        $isOurDomain = $host === self::CANONICAL_HOST || str_ends_with($host, '.' . self::CANONICAL_HOST);
        if (!$isOurDomain) {
            return;
        }

        $needsHostFix = $host !== self::CANONICAL_HOST;
        $needsHttps = !$request->isSecure();

        if (!$needsHostFix && !$needsHttps) {
            return;
        }

        $target = 'https://' . self::CANONICAL_HOST . $request->getRequestUri();
        $event->setResponse(new RedirectResponse($target, 301));
    }
}
