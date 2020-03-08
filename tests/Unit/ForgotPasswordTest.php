<?php

namespace Tests\Unit;

use Illuminate\Support\Str;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    /**
     *  On forgot password
     *  get the token to recover it
     *  provided the valid email address
     *
     * @test
     */
    public function generate_a_token_to_recover_password()
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
        $this->post(route('forgot.password'),$data)
            ->assertStatus(200);

    }

    /**
     * Cannot get a token if invalid
     * email address is provided
     *
     *@test
     */
    public function get_an_error_on_invalid_email_address_provided_on_forgot_password()
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
         * make the user email invalid
         */
        $data['email'] = Str::random() . $data['email'];

        /*
         *  Throw an error
         *  when an invalid email is provided
         *  when try to receive a token
         */
        $this->json('Post', route('forgot.password'), $data,[
            'Accept' => 'application/json'
        ])
            ->assertStatus(400);
    }
}
