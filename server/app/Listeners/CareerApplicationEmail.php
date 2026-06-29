<?php

namespace App\Listeners;

use App\Events\CareerApplicationSaved as Event;
use App\Services\EmailService;

class CareerApplicationEmail
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function handle(Event $event): void
    {
        $careerApplication = $event->getCareerApplication();
        $careerApplication->load('career');

        $site = $careerApplication->sites()->first();
        $to = $site?->contact_email ?: config('mail.fallback_to');

        // build and add email to queue for client
        $this->emailService->buildEmail(
            'careerApplication',
            $to,
            'Žádost o pracovní pozici '.$careerApplication->career->name,
            data: ['careerApplication' => $careerApplication, 'type' => 'client', 'site' => $site]
        );

        // build and add email to queue for employee
        $this->emailService->buildEmail(
            'careerApplication',
            $to,
            'Nová žádost o pracovní pozici '.$careerApplication->career->name,
            data: ['careerApplication' => $careerApplication, 'type' => 'admin', 'site' => $site]
        );
    }
}
