<?php

namespace App\Services;

use App\Models\Email;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function queue(string $from, string $to, string $subject, string $template, array $attachments = null, array $parameters = null)
    {
        $email = new Email();
        $data = [
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
            'html' => $this->getTemplate($template, true, $parameters),
            'plain' => $this->getTemplate($template, false, $parameters),
            'attachments' => $attachments,
        ];

        $email->fill($data);
        $email->save();
    }

    public function send()
    {
        $emails = Email::query()->where('sent_at', '=', null)->get();

        $emailsIds = [];
        foreach ($emails as $email) {
            Mail::send(
                [],
                [],
                function ($message) use ($email) {
                    $message->from($email->from)
                        ->to($email->to)
                        ->subject($email->subject)
                        ->html($email->html, 'utf-8')
                        ->text($email->plain, 'utf-8');

                    // multi attachments
                    if (!empty($email->attachments)) {
                        $attachments = json_decode($email->attachments, true);

                        if (json_last_error() === 0) {
                            if ($attachments && is_array($attachments) && count($attachments) > 0) {
                                foreach ($attachments as $attachment) {
                                    if (is_readable($attachment)) {
                                        $message->attach($attachment);
                                    }
                                }
                            }
                        }
                    }
                }
            );
            $emailsIds[] = $email->id;
        }

        Email::query()->whereIn('id', $emailsIds)->update(['sent_at' => Carbon::now()]);
    }

    private function getTemplate(string $template, bool $html = false, array $parameters = null)
    {
        $ext = $html ? '.html' : '.plain';

        if (view()->exists(sprintf('emails/%s', $template) . $ext)) {
            return view(sprintf('emails/%s', $template) . $ext, $parameters)->render();
        }

        return null;
    }
}
