<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Validation\ValidationException;
use Validator;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends ApiController
{

    /*
   |--------------------------------------------------------------------------
   | Forgot Password Controller
   |--------------------------------------------------------------------------
   |
   | This controller is responsible for generating tokens if the
   | valid email(appears on the database) is provided on the post request.
   | Here we are utilizing the SendsPasswordResetEmails trait
   | which comes out of the box. Feel free to explore this trait.
   |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param Request $request
     * @return HttpResponse
     * @throws ValidationException
     */
    public function getResetToken(Request $request)
    {

        /*
         * make sure the request has an email field
         */
        $this->validate($request, ['email' => 'required|email']);

        if ($request->wantsJson()) {

            $user = User::where('email', $request->input('email'))->first();

            /*
             * Verify the provided email exist on the database
             * if not then return the appropriate response
             */
            if (!$user) {

                return $this->setStatusCode(HttpResponse::HTTP_BAD_REQUEST)
                    ->respondWithError(trans('passwords.user'));

            }

            /*
             * generate a token for the password recovery
             */
            $token = $this->broker()->createToken($user);

            /*
             *  Save the response
             *  to pass it after logging
             */
            $response = $this->respondCreated([
                'result' => 'Token is created successfully.',
                'token' => $token
            ]);

            /*
             *  Log the success response
             */
            $this->log($response);

            return $response;

        }
    }


}
