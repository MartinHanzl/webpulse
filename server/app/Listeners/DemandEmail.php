<?php

namespace App\Listeners;

use App\Events\DemandSaved as Event;
use App\Services\EmailService;

class DemandEmail
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function handle(Event $event): void
    {
        $demand = $event->getDemand();

        $site = $demand->sites()->first();
        $to = $site?->contact_email ?: config('mail.fallback_to');

        $this->emailService->buildEmail(
            'demand',
            $to,
            'Nová poptávka',
            data: ['demand' => $demand, 'site' => $site],
            locale: $demand->locale,
        );
    }
}
