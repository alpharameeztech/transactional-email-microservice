<?php

namespace App\Http\Controllers\Api;

use App\Jobs\SendEmail;
use App\SentEmail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailController extends ApiController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function send(Request $request)
    {

        $validatedData = $this->validateData($request);

        /*
         * Send the email to the user
         */
        SendEmail::dispatch($validatedData)
            ->onQueue('email');

        /*
        * Save the record
        */
        $sent = new SentEmail();
        $sent->store($validatedData);

        /*
         * Since the email will be send from a queue
         * we need to return the response
         */
        return  $this->respondCreated([
            'result' => 'Email is in queue and will be on its way very soon.',
            'service' => config('mail.service')
        ]);
    }



    protected function validateData(Request $request){

        return $request->validate([
            'to' => 'required|max:255',
            'subject' => 'required|max:255',
            'message' => 'required',
        ]);

    }
}
