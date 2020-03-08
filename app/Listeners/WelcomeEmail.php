<?php

namespace App\Listeners;

use App\Events\NewUserRegistered;
use App\Jobs\SendEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class WelcomeEmail implements ShouldQueue
{

    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  NewUserRegistered  $event
     * @return void
     */
    public function handle(NewUserRegistered $event)
    {
        /*
         * A new registered user
         */
        $user = $event->user->toArray();


        /*
         * prepare the data
         * to send to mail job
         */
        $data = [
            'to' => $user['email'],
            'subject' => 'Thank you for registration',
            'message' => 'You have successfully registered with Takeaway.com'
        ];

        /*
         * dispatch the email sending job
         */
        SendEmail::dispatch($data)
            ->onQueue('email');
    }
}
