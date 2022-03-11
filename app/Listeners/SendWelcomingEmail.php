<?php

namespace App\Listeners;

use App\Events\UserLoginEvent;
use App\Mail\UserWelcome;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class SendWelcomingEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserLoginEvent $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(UserLoginEvent $event)
    {
        try {
            Mail::to($event->user)->send(new UserWelcome($event->user));
        }catch (TransportException $e){
            return response()->json([
                'message' => $e->getMessage(),
                'solution' => 'change MAIL_HOST to localhost and open your APP_URL:8025 to display MailHog'
            ], 500);
        }
    }
}
