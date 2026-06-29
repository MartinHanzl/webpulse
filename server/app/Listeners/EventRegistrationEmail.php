<?php

namespace App\Listeners;

use App\Events\EventRegistrationSaved as Event;
use App\Services\EmailService;

class EventRegistrationEmail
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function handle(Event $event): void
    {
        $eventRegistration = $event->getEventRegistration();
        $eventRegistration->load('event');

        $site = $eventRegistration->event?->sites()->first();
        $to = $site?->contact_email ?: config('mail.fallback_to');

        $this->emailService->buildEmail(
            'eventRegistration',
            $to,
            'Registrace na akci '.$eventRegistration->event->name,
            data: ['eventRegistration' => $eventRegistration, 'site' => $site]
        );
    }
}
