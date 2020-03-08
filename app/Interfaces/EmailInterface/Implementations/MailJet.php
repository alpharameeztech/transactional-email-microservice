<?php

namespace App\Interfaces\EmailInterface\Implementations;

use App\Interfaces\EmailInterface\Email;
use App\Transformers\Json;
use Carbon\Carbon;
use Mailjet\Client;
use Mailjet\Resources;
use Exception;

class MailJet implements Email
{

    public function send($data)
    {
        $mj = new Client(env('MJ_APIKEY_PUBLIC'), env('MJ_APIKEY_PRIVATE'),true,['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => config('mail.from.address'),
                        'Name' => config('mail.from.name')
                    ],
                    'To' => [
                        [
                            'Email' => $data['to'],
                        ]
                    ],
                    'Subject' => $data['subject'],
                    'TextPart' => $data['message']
                ]
            ]

        ];
        try {

            $response = $mj->post(Resources::$Email, ['body' => $body]);

            if ($response->success()) {

                /*
                 *  On success call the log method
                 *  to log response
                 */
                return $this->log($data);

            }

        } catch(Exception $e) {

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
        /*
         *  Transform into json response
         *  and log the response
         */
        \Log::info(Json::response(
            $data,
            'Email successfully sent to ' . $data['to'] . ' using ' . get_called_class() . ' on ' . Carbon::now()
        ));
    }
}
