<?php

namespace App\Http\Controllers\Api;

use App\Jobs\SendEmail;
use App\SentEmail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Validator;

class EmailController extends ApiController
{

    /**
     * Get all the sent emails.
     *
     * @return HttpResponse
     */
    public function index()
    {
        return SentEmail::get();
    }

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
         * If there are validation error
         * return the appropriate response
         */
        if($this->getStatusCode() == HttpResponse::HTTP_BAD_REQUEST){
            return $validatedData;
        }

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

        $validator = Validator::make($request->all(), [
            'to' => 'required|max:255',
            'subject' => 'required|max:255',
            'message' => 'required',
        ]);

        if ($validator->fails()) {

            return  $this->setStatusCode(400)
                ->respondWithError([
                    'result' => 'Oops, something went wrong',
                    'error' => $validator->errors()
                ]);

        }else{

            return $request->validate([
                'to' => 'required|max:255',
                'subject' => 'required|max:255',
                'message' => 'required',
            ]);
        }

    }
}
