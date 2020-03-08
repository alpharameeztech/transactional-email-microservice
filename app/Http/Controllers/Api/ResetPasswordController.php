<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Validator;
use Illuminate\Http\Response as HttpRespnse;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends ApiController
{
    /*
   |--------------------------------------------------------------------------
   | Password Reset Controller
   |--------------------------------------------------------------------------
   |
   | This controller is responsible for handling password reset requests.
   | This will require the email,password,password_confirmation and
   | token on the post request in order to validate and reset.
   | This uses a simple trait to include this behavior.
   | You're free to explore this trait and override any methods you wish to tweak.
   |
    */

    use ResetsPasswords;

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
     * Reset the given user's password.
     *
     * @param Request $request
     * @return HttpRespnse
     * @throws ValidationException
     */
    public function reset(Request $request)
    {
        /*
         *  Make sure the request have email,token,
         *  password and password_confirmation
         */
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        /*
         * Here we will attempt to reset the user's password. If it is successful we
         * will update the password on an actual user model and persist it to the
         * database. Otherwise we will parse the error and return the response.
         */
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        if ($request->wantsJson()) {

                /*
                 *  If password has been successfully reset
                 *  then generate a success response
                 */
                $response =  $this->respondCreated([
                    'result' => trans('passwords.reset'),
                    'email' => $request->input('email'),
                ]);

                /*
                *  Log the success response
                */
                $this->log($response);

                return $response;

        }

    }
}
