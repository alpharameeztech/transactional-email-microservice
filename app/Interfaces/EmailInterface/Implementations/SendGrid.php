<?php

namespace App\Interfaces\EmailInterface\Implementations;

use App\Interfaces\EmailInterface\Email;
use App\Transformers\Json;
use Carbon\Carbon;
use SendGrid\Mail\Mail as SendGridMail;
use SendGrid as SendGridService;

class SendGrid implements Email
{

    public function send($data)
    {
        $email = new SendGridMail();
        $email->setFrom(config('mail.from.address'), config('mail.from.name'));
        $email->setSubject($data['subject']);
        $email->addTo($data['to']);
        $email->addContent("text/plain", $data['message']);
        $sendgrid = new SendGridService(env('SENDGRID_API_KEY'));

        try {

            $sendgrid->send($email);

            /*
             *  On success call the log method
             *  to log response
             */
            return $this->log($data);

        } catch (Exception $e) {

            /*
             * if the default emails service is down
             * for any reason,
             * then throw an error
             * that will be handled by the queued job
             * which will trigger fallback service
             */
            throw $e;
        }
    }

    public function log($data)
    {
        /* Transform into json response
         * and log the response
         */
        \Log::info(Json::response(
            $data,
            'Email successfully sent to ' . $data['to'] . ' using ' . get_called_class() . ' on ' . Carbon::now()
        ));
    }
}
