<?php

namespace App\Http\Controllers\API;

use App\Events\NewUserRegistered;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Http\Response as HttpRespnse;

class RegisterController extends ApiController
{

    /**
     * Register api
     *
     * @return HttpRespnse
     */
    public function register(Request $request)
    {

        $validator = $this->validateData($request);

        /*
         * if the validation failed
         * then return the appropiate errors
         */
        if($validator->fails()){

            return $this->setStatusCode(HttpRespnse::HTTP_BAD_REQUEST)
                ->respondWithError($validator->errors());

        }

        /*
         * validation is passed
         * now create a user
         */
        $user =  User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        /*
         * broadcast the event
         * on new user registration
         */
        event(new NewUserRegistered($user));

        /*
         * Save the response
         * to pass it after logging
         */
        $response = $this->setStatusCode(HttpRespnse::HTTP_CREATED)
                        ->respondCreated([
                            'result' => 'A new user successfully registered with the email: ' . $user->email . '. A welcome email is also sent',
                            'service' => config('mail.service')
                        ]);

        /*
         *  Log the success response
         */
        $this->log($response);

        return $response;
    }

    protected function validateData(Request $request){

        return Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required',  'confirmed', 'string', 'min:8'],
        ]);
    }
}
