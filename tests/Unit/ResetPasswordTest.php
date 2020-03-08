<?php

namespace Tests\Unit;

use Illuminate\Support\Str;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    /**
     *  Reset password
     *  provided valid email, password,
     *  password_confirmation and token
     *
     * @test
     */
    public function reset_password_with_a_valid_token(){

        /*
        *  Generate a fake password
        */
        $password = $this->faker->password;

        /*
         *  Generate data for a new user
         */
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password
        ];

        /*
         *  Create a new user
         */
        $this->post(route('user.register'), $data);

        /*
         *  Generate the token to recover password
         *  for the provided user's email
         */
        $response = $this->json('Post', route('forgot.password'), $data);

        $token = $response['data']['message']['token'];

        $newPassword = "someNewPassword";

        /*
         *  Provide the valid token
         *  and new password to set
         */
        $data['token'] = $token;
        $data['password'] = $newPassword;
        $data['password_confirmation'] = $newPassword;

        /*
         *  Reset the password
         *  with a token
         */
        $response = $this->json('Post', route('reset.password'), $data)
            ->assertStatus(200);

    }

    /**
     * Cannot reset password
     * if invalid token is provided
     *
     * @test
     */
    public function get_an_error_on_invalid_token_provided_on_reset_password()
    {

        /*
        *  Generate a fake password
        */
        $password = $this->faker->password;

        /*
         *  Generate data for a new user
         */
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password
        ];

        /*
         *  Create a new user
         */
        $this->post(route('user.register'), $data);

        /*
         *  Generate the token to recover password
         *  for the provided user's email
         */
        $response = $this->json('Post', route('forgot.password'), $data);

        $token = $response['data']['message']['token'];

        $newPassword = "someNewPassword";

        /*
         *  Make the user token invalid
         */
        $data['token'] = Str::random() . $token;
        $data['password'] = $newPassword;
        $data['password_confirmation'] = $newPassword;

        /*
         *  Throw a an error
         *  when invalid token is provided
         *  when try to reset the password
         */
        $response = $this->json('Post', route('reset.password'), $data)
            ->assertStatus(400);

    }

}
